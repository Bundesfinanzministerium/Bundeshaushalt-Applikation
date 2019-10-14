<?php
namespace PPKOELN\BmfBudget\Domain\Validator\Tce;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class DisplayCondition
{
    /**
     * Returns valid if condition is TRUE
     *
     * @param array $param1
     * @return bool
     */
    public function showIfLevel($param1)
    {
        if ($param1['record']['address'] !== '') {
            if ($param1['conditionParameters'][0] === 'greater') {
                return strlen($param1['record']['address']) > (int)$param1['conditionParameters'][1];
            }
            if ($param1['conditionParameters'][0] === 'lower') {
                return strlen($param1['record']['address']) < (int)$param1['conditionParameters'][1];
            }
            if ($param1['conditionParameters'][0] === 'equal') {
                return strlen($param1['record']['address']) === (int)$param1['conditionParameters'][1];
            }
        }
        return true;
    }
}
