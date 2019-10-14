<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\SingletonInterface;

class ChildParser extends AbstractParser implements SingletonInterface
{

    public function get($account = '', $flow = '', $childs = [], $address = '')
    {
        $retval = [];
        if (strtolower($childs['type']) === 'structure') {
            foreach ($childs['entities'] as $entity) {
                $retval[] = [
                    'a' => $entity->getAddress(),
                    't' => $this->formatAddress($entity),
                    'l' => $entity->getTitle(),
                    'v' => (string)$entity->getRevenue($account, $flow),
                    'f' => '-1',
                    's' => '0',
                ];
            }
        } elseif (strtolower($childs['type']) === 'title') {
            foreach ($childs['entities'] as $entity) {
                $retval[] = [
                    'a' => $entity->getAddress(),
                    't' => $this->formatAddress($entity),
                    'l' => $entity->getTitle(),
                    'v' => (string)$entity->getCurrentRevenue($account, $flow),
                    'f' => $entity->getFlexible() ? '1' : '0',
                    's' => '0',
                    'titleDetail' => $this->getTitelDetails($entity, $account)
                ];
            }
        }
        return $retval;
    }
}
