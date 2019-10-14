<?php
namespace PPKOELN\BmfBudget\Controller\Backend\Maintenance;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TotallingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * BudgetRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * Service: BackendTotalingSection
     *
     * @var \PPKOELN\BmfBudget\Service\Backend\Totaling\SectionService
     * @inject
     */
    protected $serviceBackendTotalingSection;

    /**
     * Service: BackendTotalingGroup
     *
     * @var \PPKOELN\BmfBudget\Service\Backend\Totaling\GroupService
     * @inject
     */
    protected $serviceBackendTotalingGroup;

    /**
     * Service: BackendTotalingFunction
     *
     * @var \PPKOELN\BmfBudget\Service\Backend\Totaling\FunctionService
     * @inject
     */
    protected $serviceBackendTotalingFunction;

    /**
     * Initial index action
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\Totalling $totallingform Main user form
     *
     * @return null
     */
    public function indexAction(
        \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto $totallingform = null
    ) {

        $this->view->assign('budgets', $this->budgetRepository->findAll());

        if ($totallingform instanceof \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto) {
            switch (strtolower($totallingform->getStructure())) {
                case 'section':
                    $totals = $this->serviceBackendTotalingSection->process($totallingform->getBudget());
                    break;

                case 'group':
                    $totals = $this->serviceBackendTotalingGroup->process($totallingform->getBudget());
                    break;

                case 'function':
                    $totals = $this->serviceBackendTotalingFunction->process($totallingform->getBudget());
                    break;

                default:
                    $totals = false;
            }

            $this->view->assign('totallingform', $totallingform);

            $this->view->assign('totals', $totals);
        }

        return null;
    }
}
