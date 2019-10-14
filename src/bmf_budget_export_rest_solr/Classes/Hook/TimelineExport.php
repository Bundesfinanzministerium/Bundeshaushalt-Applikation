<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Hook;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\SingletonInterface;
use \PPKOELN\BmfBudgetCrawler\Hook\HookInterface;

class TimelineExport implements HookInterface, SingletonInterface
{
    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;


    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Timeline\RootTimelineService
     * @inject
     */
    protected $rootTimelineService;


    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Timeline\StructureTimelineService
     * @inject
     */
    protected $structureTimelineService;

    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Timeline\TitleTimelineService
     * @inject
     */
    protected $titleTimelineService;

    /**
     * Write Service
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\TimelineWriteService
     * @inject
     */
    protected $writeService;

    /**
     * Scheduler service
     *
     * @var \PPKOELN\BmfBudgetCrawler\Service\TimelineSchedulerService
     * @inject
     */
    protected $schedulerService;

    /**
     * Return the hook title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'BMF, budget timeline visualization - rest solr exporter';
    }

    /**
     * Hook preprocess
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function preProc(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        $this->schedulerService->write(
            $crawler,
            $this->schedulerService->analyse()
        );
        return null;
    }

    /**
     * Hook process
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue
     * @param string $result
     * @param int $error
     * @return string
     */
    public function proc(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue &$queue,
        &$result,
        &$error
    ) {
        /**
         * Preparing address
         */
        $addressValue = $queue->getAddress();

        if (strpos($addressValue, '-') !== false) {
            $value = explode('-', $addressValue);
            $structure = $value[0];
            $address = $value[1];
        } else {
            $structure = '';
            $address = $addressValue;
        }

        $entites = [];
        $result = null;

        if (strlen($address) === 0 || $address === 'root') {
            $entityService = $this->rootTimelineService;
        } elseif (strlen($address) === 9) {
            $entityService = $this->titleTimelineService;
        } else {
            $entityService = $this->structureTimelineService;
        }



        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get(
            \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class
        );
        $querySettings->setRespectStoragePage(false);

        $this->budgetRepository->setDefaultOrderings(
            [
            'year' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
            ]
        );

        $this->budgetRepository->setDefaultQuerySettings(
            $querySettings
        );

        $budgets = $this->budgetRepository->findAll();

        foreach ($budgets as $budget) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Budget $budget */
            try {
                $entites[] = $entityService->get($budget, $structure, $address);
            } catch (\Exception $e) {
                // do nothing here, the solr query result returns invalid addresses like rest-timeline/funktion/960.html
            }
        }

        $this->writeService->json(
            $queue->getCrawler()->getBudget(),
            $structure,
            $address,
            $entites
        );

        return null;
    }
}
