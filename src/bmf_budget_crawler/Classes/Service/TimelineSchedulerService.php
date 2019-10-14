<?php
namespace PPKOELN\BmfBudgetCrawler\Service;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Service\AbstractService;
use \TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TimelineSchedulerService extends AbstractService implements SingletonInterface
{
    /**
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository
     * @inject
     */
    protected $crawlerRepository;

    /**
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\QueueRepository
     * @inject
     */
    protected $queueRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \PPKOELN\BmfBudget\Service\Backend\LogService
     * @inject
     */
    protected $logService;

    /**
     * @var \PPKOELN\BmfBudgetCrawler\Service\TimelineHookService
     * @inject
     */
    protected $hookService;

    /**
     * Analyse tree and prepare writing
     *
     * @return array
     * @throws \ApacheSolrForTypo3\Solr\NoSolrConnectionFoundException
     */
    public function analyse()
    {
        /** @var \ApacheSolrForTypo3\Solr\ConnectionManager $connectionManager */
        $connectionManager = GeneralUtility::makeInstance(\ApacheSolrForTypo3\Solr\ConnectionManager::class);
        $solr = $connectionManager->getConnectionByPageId(2);

        // http://192.168.195.17:8983/solr/core_de/select?fl=timelineIdentifier&group.field=timelineIdentifier&group=true&indent=on&q=timelineIdentifier:*&wt=json
        /** @var \Apache_Solr_Response $response */
        $response = $solr->getReadService()->search(
            'timelineIdentifier:*',
            0,
            999999,
            [
                'fl' => 'timelineIdentifier',
                'group.field' => 'timelineIdentifier',
                'group' => 'true'
            ]
        );

        $result = [];
        if ($response->getHttpStatus() === 200) {
            $groupedResults = $solr->getReadService()->getResponse()->__get('grouped');
            foreach ($groupedResults->timelineIdentifier->groups as $group) {
                $result[$group->groupValue] = $group->groupValue;
            }
        }
        return $result;
    }

    /**
     * Write records
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @param array $data
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function write(\PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler, array $data = [])
    {
        foreach ($data as $value) {
            $queue = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
            $queue->setCrawler($crawler);
            $queue->setAddress($value);
            $queue->setStatus(0);
            $this->queueRepository->add($queue);
        }
        $this->persistenceManager->persistAll();
    }
}
