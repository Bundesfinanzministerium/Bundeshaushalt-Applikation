<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\SingletonInterface;

class DetailParser extends AbstractParser implements SingletonInterface
{

    public function get($detail = [], $structure = '', $account = '', $flow = '')
    {
        $retval = [];
        if (strtolower($detail['type']) === 'root') {
            $getTitle = 'getTitle' . ucfirst($account);
            $getValue = 'get' . ucfirst($structure) . ucfirst($account) . ucfirst($flow);
            $retval['address'] = '';
            $retval['text'] = '____ ___ __ - ___';
            $retval['label'] = $this->removeLn($detail['entity']->$getTitle());
            $retval['value'] = (string)$detail['entity']->$getValue();
            $retval['typ'] = 'root';
        } elseif (strtolower($detail['type']) === 'title') {
            $retval['address'] = $detail['entity']->getAddress();
            $retval['text'] = $this->formatAddress($detail['entity']);
            $retval['label'] = $this->removeLn($detail['entity']->getTitle());
            $retval['value'] = (string)$detail['entity']->getCurrentRevenue($account, $flow);
            $retval['typ'] = 'title';
            $retval['titleDetail'] = $this->getTitelDetails($detail['entity'], $account);
        } elseif (strtolower($detail['type']) === 'structure') {
            $retval['address'] = $detail['entity']->getAddress();
            $retval['text'] = $this->formatAddress($detail['entity']);
            $retval['label'] = $this->removeLn($detail['entity']->getTitle());
            $retval['value'] = (string)$detail['entity']->getRevenue($account, $flow);
            $retval['typ'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'json.detail.' . $structure,
                'bmf_budget_export_rest_solr'
            );
        }
        return $retval;
    }
}
