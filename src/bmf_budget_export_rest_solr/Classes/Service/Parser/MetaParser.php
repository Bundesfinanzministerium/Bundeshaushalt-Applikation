<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MetaParser extends AbstractParser implements SingletonInterface
{
    public function get($structure = '', $year = '', $account = '', $flow = '', $address = '')
    {
        return [
            'unit' => LocalizationUtility::translate(
                'json.structure.' . $structure,
                'bmf_budget_export_rest_solr'
            ),
            'year' => $year,
            'quota' => LocalizationUtility::translate(
                'json.account.' . $account,
                'bmf_budget_export_rest_solr'
            ),
            'account' => LocalizationUtility::translate(
                'json.flow.' . $flow,
                'bmf_budget_export_rest_solr'
            ),
            'levelCur' => (string)$this->getCurrentLevel($structure, $address),
            'levelMax' => (string)$this->getMaxLevel($structure)
        ];
    }
}
