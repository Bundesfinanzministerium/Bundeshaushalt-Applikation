<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Timeline;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class RootTimelineService
{
    public function get(\PPKOELN\BmfBudget\Domain\Model\Budget $budget = null, $structure = '')
    {
        return [
            $budget->getYear() => [
                'label' => [
                    'target' => $budget->getTitleTarget(),
                    'actual' => $budget->getTitleActual()
                ],
                'income' => [
                    'target' => method_exists($budget, 'get' . ucfirst($structure) . 'TargetIncome')
                        ? $budget->{'get' . ucfirst($structure) . 'TargetIncome'}() * 1000
                        : null,
                    'actual' => method_exists($budget, 'get' . ucfirst($structure) . 'ActualIncome')
                        ? $budget->{'get' . ucfirst($structure) . 'ActualIncome'}()
                        : null
                ],
                'expenses' => [
                    'target' => method_exists($budget, 'get' . ucfirst($structure) . 'TargetExpenses')
                        ? $budget->{'get' . ucfirst($structure) . 'TargetExpenses'}() * 1000
                        : null,
                    'actual' => method_exists($budget, 'get' . ucfirst($structure) . 'ActualExpenses')
                        ? $budget->{'get' . ucfirst($structure) . 'ActualExpenses'}()
                        : null
                ]
            ]
        ];
    }
}
