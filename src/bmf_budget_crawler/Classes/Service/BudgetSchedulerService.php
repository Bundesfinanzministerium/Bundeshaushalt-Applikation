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

class BudgetSchedulerService extends AbstractService implements SingletonInterface
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
     * Analyse tree and prepare writing
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return array
     * @throws \Exception
     */
    public function analyse(\PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler)
    {
        $result = [];
        switch ($crawler->getStructure()) {
            case "section":
                $entities = $crawler->getBudget()->getSections();
                break;
            case "function":
                $entities = $crawler->getBudget()->getFunctions();
                break;
            case "group":
                $entities = $crawler->getBudget()->getGroups();
                break;
            default:
                throw new \Exception('Undefined structure');
        }

        $this->rotate($entities, $crawler, $result);

        foreach (array_keys($result) as $key) {
            array_unshift($result[$key], "");
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
        foreach ($data as $key => $value) {
            set_time_limit(240);
            $addressCurrent = 0;

            foreach ($value as $address) {
                set_time_limit(240);

                $queue = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
                $queue->setCrawler($crawler);
                $queue->setAddress($address);
                $queue->setStatus(0);
                $this->queueRepository->add($queue);

                if ($addressCurrent++ % 250 === 0) {
                    $this->persistenceManager->persistAll();
                }
            }
            $this->persistenceManager->persistAll();
            unset($data[$key]);
        }
    }

    /**
     * @param $entities
     * @param $crawler
     * @param $result
     * @return mixed
     */
    protected function rotate($entities, &$crawler, &$result)
    {
        foreach ($entities as $entity) {
            // Processing children
            $children = [];
            $praefix = '';

            if ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Section) {
                $children = $entity->getSections();
                $praefix = 'section';
            } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Functione) {
                $children = $entity->getFunctions();
                $praefix = 'function';
            } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Groupe) {
                $children = $entity->getGroups();
                $praefix = 'group';
            }

            if (count($children) > 0) {
                $this->rotate($children, $crawler, $result);
            }

            // Processing titles
            $titles = $entity->getTitles();
            if (count($titles) > 0) {
                foreach ($titles as $title) {
                    /** @var \PPKOELN\BmfBudget\Domain\Model\Title $title */

                    if ($crawler->getAccount() === 'actual'
                        && $crawler->getFlow() === 'income'
                        && $title->getActualIncome() !== null
                    ) {
                        $result[$praefix . '-actual-income'][] = $title->getAddress();
                    } elseif ($crawler->getAccount() === 'actual'
                        && $crawler->getFlow() === 'expenses'
                        && $title->getActualExpenses() !== null
                    ) {
                        $result[$praefix . '-actual-expenses'][] = $title->getAddress();
                    } elseif ($crawler->getAccount() === 'target'
                        && $crawler->getFlow() === 'income'
                        && $title->getCurrentTargetIncome() !== null
                    ) {
                        $result[$praefix . '-target-income'][] = $title->getAddress();
                    } elseif ($crawler->getAccount() === 'target'
                        && $crawler->getFlow() === 'expenses'
                        && $title->getCurrentTargetExpenses() !== null
                    ) {
                        $result[$praefix . '-target-expenses'][] = $title->getAddress();
                    }
                }
            }

            if ($crawler->getAccount() === 'actual'
                && $crawler->getFlow() === 'income'
                && $entity->getActualIncome() !== null
            ) {
                $result[$praefix . '-actual-income'][] = $entity->getAddress();
            } elseif ($crawler->getAccount() === 'actual'
                && $crawler->getFlow() === 'expenses'
                && $entity->getActualExpenses() !== null
            ) {
                $result[$praefix . '-actual-expenses'][] = $entity->getAddress();
            } elseif ($crawler->getAccount() === 'target'
                && $crawler->getFlow() === 'income'
                && $entity->getTargetIncome() !== null
            ) {
                $result[$praefix . '-target-income'][] = $entity->getAddress();
            } elseif ($crawler->getAccount() === 'target'
                && $crawler->getFlow() === 'expenses'
                && $entity->getTargetExpenses() !== null
            ) {
                $result[$praefix . '-target-expenses'][] = $entity->getAddress();
            }
        }
        return $result;
    }
}
