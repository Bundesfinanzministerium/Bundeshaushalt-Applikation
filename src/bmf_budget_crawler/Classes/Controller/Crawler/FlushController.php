<?php
namespace PPKOELN\BmfBudgetCrawler\Controller\Crawler;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FlushController extends ActionController
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
     * Main action
     *
     * @param string $result
     * @return void
     */
    public function indexAction($result = null)
    {
        if ($result !== null) {
            $this->view->assign('result', $result);
        }
    }

    /**
     * Process action
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connectionPool->getConnectionForTable('tx_bmfbudgetcrawler_domain_model_crawler')
            ->truncate('tx_bmfbudgetcrawler_domain_model_crawler');
        $connectionPool->getConnectionForTable('tx_bmfbudgetcrawler_domain_model_queue')
            ->truncate('tx_bmfbudgetcrawler_domain_model_queue');

        $this->forward('index', null, null, ['result' => 'done']);
    }
}
