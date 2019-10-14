<?php
namespace PPKOELN\BmfBudget\Service\Structure;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractStructureService extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @param null   $storage
     * @param string $plan
     * @param string $typ
     *
     * @return array
     */
    protected function parseStructureList(
        \TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage $storage = null,
        $account = '',
        $flow = '',
        $activeAddress = ''
    ) {
        $list = [];
        $total = 0;
        $count = 0;
        $sort = [];

        foreach ($storage as $entity) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\AbstractValue $entity */
            if (($value = $entity->getRevenue($account, $flow)) !== false) {
                $list[] = $entity;
                $sort[] = $value;
                if ($value >= 0) {
                    $total += $value;
                    $count++;
                }
            }
        }
        arsort($sort);
        $ordered = [];
        foreach ($sort as $key => $value) {
            $ordered[] = $list[$key];
        }

        return [
            'type' => 'Structure',
            'entities' => $ordered,
            'total' => $total,
            'count' => $count,
            'active' => $activeAddress
        ];
    }

    /**
     * @param null   $storage
     * @param string $plan
     * @param string $typ
     *
     * @return array
     */
    protected function parseTitelList(
        $storage,
        $account,
        $flow,
        $activeAddress = ''
    ) {
        if (!$storage) {
            $storage = [];
        }
        $list = [];
        $total = 0;
        $count = 0;
        $sort = [];

        foreach ($storage as $entity) {
            if (($value = $entity->getCurrentRevenue($account, $flow)) !== false) {
                $list[] = $entity;
                $sort[] = $value;
                if ($value >= 0) {
                    $total += $value;
                    $count++;
                }
            }
        }

        array_multisort($sort, SORT_DESC, $list);

        return [
            'type' => 'Title',
            'entities' => $list,
            'total' => $total,
            'count' => $count,
            'active' => $activeAddress
        ];
    }
}
