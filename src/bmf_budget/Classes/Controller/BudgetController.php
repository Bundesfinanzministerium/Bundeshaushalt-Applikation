<?php
namespace PPKOELN\BmfBudget\Controller;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class BudgetController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

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
     * Service Page Title
     *
     * @var \PPKOELN\BmfBudget\Service\Page\TitleService
     * @inject
     */
    protected $servicePageTitle;

    /**
     * Main action
     *
     * @param string $year      Corresponding jahr, possible values: 2012, 2013, ...
     * @param string $account   Corresponding plan, possible values: soll, ist
     * @param string $flow      Corresponding stromgroesse, possible values: einnahmen, ausgaben
     * @param string $structure Corresponding struktur, possible values: einzelplan, gruppe, funktion
     * @param string $address   Corresponding address, possible values: 0, 01, 012, 0123, 0123
     */
    public function showAction(
        $year = '',
        $account = '',
        $flow = '',
        $structure = '',
        $address = ''
    ) {

        $result = null;

        if (!($budget = $this->budgetRepository->findByYear($year)->getFirst())) {
            $this->servicePageTitle->set(null, null);
            $this->forward('intro');
            return null;
        }

        /**
         * Check segment parameters
         */
        if (!($account = $this->translate2origin('account', $account))) {
            $this->addFlashMessage('Invalid account parameter', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);

            return null;
        }
        if (!($flow = $this->translate2origin('flow', $flow))) {
            $this->addFlashMessage('Invalid flow parameter', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);

            return null;
        }
        if (!($structure = $this->translate2origin('structure', $structure))) {
            $this->addFlashMessage('Invalid structure parameter', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);

            return null;
        }

        /**
         * Process request
         */
        switch (strtolower($structure)) {
            case "section":
                $result = $this->serviceStructureSection->get($budget, $account, $flow, $address);
                break;
            case "function":
                $result = $this->serviceStructureFunction->get($budget, $account, $flow, $address);
                break;
            case "group":
                $result = $this->serviceStructureGroup->get($budget, $account, $flow, $address);
                break;
            default:
        }

        $referrer = [
            'year' => $year,
            'account' => $account,
            'flow' => $flow,
            'structure' => $structure,
            'address' => $address
        ];

        if ($result) {
            $this->servicePageTitle->set($result['detail'], $referrer);
            $this->view->assign('budgets', $this->budgetRepository->findAll());
            $this->view->assign('result', $result);
            $this->view->assign('referrer', $referrer);

            return null;
        } else {
            $GLOBALS['TSFE']->pageNotFoundAndExit('Die gewünschte Position wurde nicht gefunden.');
        }
    }

    public function introAction()
    {

        if (!($budget = $this->budgetRepository->findByIdentifier($this->settings['budget']))) {
            $this->view->assign('error', 'no valid budget available');
        }

        $this->view->assign('budget', $budget);
    }

    public function translate2origin($typ, $value)
    {

        $WHITE = [
            'account' => [
                LocalizationUtility::translate('segment.account.target', 'bmf_budget') => 'target',
                LocalizationUtility::translate('segment.account.actual', 'bmf_budget') => 'actual'
            ],
            'flow' => [
                LocalizationUtility::translate('segment.flow.income', 'bmf_budget') => 'income',
                LocalizationUtility::translate('segment.flow.expenses', 'bmf_budget') => 'expenses'
            ],
            'structure' => [
                LocalizationUtility::translate('segment.structure.section', 'bmf_budget') => 'section',
                LocalizationUtility::translate('segment.structure.function', 'bmf_budget') => 'function',
                LocalizationUtility::translate('segment.structure.group', 'bmf_budget') => 'group'
            ]
        ];

        return isset($WHITE[$typ][$value]) ? $WHITE[$typ][$value] : false;
    }
}
