<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPKOELN\BmfBudget\Domain\Model\AbstractValue;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use \TYPO3\CMS\Core\SingletonInterface;

class ParentParser extends AbstractParser implements SingletonInterface
{
    public function get(
        $structure,
        $account,
        $flow,
        Budget $budget = null,
        AbstractValue $entity = null,
        $rootpath = []
    ) {
        if (!$structure) {
            $structure = '';
        }
        $retval = [];

        $retval[] = [
            [
                'a' => '',
                't' => '____ ___ __ - ___',
                'l' => $this->removeLn($budget->getTitle($account)),
                'v' => (string)$budget->getRevenue($structure, $account, $flow) . '',
                'f' => '-1',
                's' => '1'
            ]
        ];

        foreach ($rootpath as $depth) {
            $tempArray = [];
            foreach ($depth['entities'] as $sub) {
                $value = [
                    'a' => $sub->getAddress(),
                    't' => $this->formatAddress($sub),
                    'l' => $this->removeLn($sub->getTitle()),
                    'v' => (string)(strtolower($depth['type']) === 'title'
                        ? $sub->getCurrentRevenue($account, $flow)
                        : $sub->getRevenue($account, $flow)),
                    'f' => (string)(strtolower($depth['type']) === 'title'
                        ? $sub->getFlexible() ? '1' : '0'
                        : '-1'),
                    's' => (string)($depth['active'] == $sub->getAddress()
                        ? '1'
                        : '0')
                ];

                if (strtolower($depth['type']) === 'title') {
                    $value['titleDetail'] = $this->getTitelDetails($sub, $account);
                }
                $tempArray[] = $value;
            }
            $retval[] = $tempArray;
        }
        return $retval;
    }
}
