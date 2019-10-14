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

class BudgetExport implements HookInterface, SingletonInterface
{

    /**
     * Service Structure Section
     *
     * @var \PPKOELN\BmfBudget\Service\Structure\SectionService
     * @inject
     */
    protected $serviceStructureSection;

    /**
     * Service Structure Function
     *
     * @var \PPKOELN\BmfBudget\Service\Structure\FunctioneService
     * @inject
     */
    protected $serviceStructureFunction;

    /**
     * Service Structure Group
     *
     * @var \PPKOELN\BmfBudget\Service\Structure\GroupeService
     * @inject
     */
    protected $serviceStructureGroup;

    /**
     * Write Service
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\WriteService
     * @inject
     */
    protected $writeService;

    /**
     * Scheduler Service
     *
     * @var \PPKOELN\BmfBudgetCrawler\Service\BudgetSchedulerService
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
        return 'BMF, budget visualization - rest solr exporter';
    }

    /**
     * Hook preprocess
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return null
     */
    public function preProc(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        $this->schedulerService->write(
            $crawler,
            $this->schedulerService->analyse($crawler)
        );
        return null;
    }

    /**
     * Hook process
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue
     * @param string $result
     * @param int $error
     *
     * @return string
     */
    public function proc(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue &$queue,
        &$result,
        &$error
    ) {

        /**
         * Process request
         */
        switch (strtolower($queue->getCrawler()->getStructure())) {
            case "section":
                $result = $this->serviceStructureSection->get(
                    $queue->getCrawler()->getBudget(),
                    $queue->getCrawler()->getAccount(),
                    $queue->getCrawler()->getFlow(),
                    $queue->getAddress()
                );
                break;
            case "function":
                $result = $this->serviceStructureFunction->get(
                    $queue->getCrawler()->getBudget(),
                    $queue->getCrawler()->getAccount(),
                    $queue->getCrawler()->getFlow(),
                    $queue->getAddress()
                );
                break;
            case "group":
                $result = $this->serviceStructureGroup->get(
                    $queue->getCrawler()->getBudget(),
                    $queue->getCrawler()->getAccount(),
                    $queue->getCrawler()->getFlow(),
                    $queue->getAddress()
                );
                break;
            default:
        }

        $this->writeService->solr(
            $queue->getCrawler()->getBudget(),
            $queue->getCrawler()->getBudget()->getYear(),
            $queue->getCrawler()->getAccount(),
            $queue->getCrawler()->getFlow(),
            $queue->getCrawler()->getStructure(),
            $queue->getAddress(),
            $result
        );

        return null;
    }
}
