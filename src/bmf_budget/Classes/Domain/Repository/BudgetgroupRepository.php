<?php
namespace PPKOELN\BmfBudget\Domain\Repository;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class BudgetgroupRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Find Object by given Section and Address
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section Corresponding object
     * @param string                                  $address Corresponding address
     *
     * @return bool|object
     */
    public function findBySectionAndTitle(
        \PPKOELN\BmfBudget\Domain\Model\Section $section = null,
        $title = ''
    ) {

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        $logicalAnd = [];
        $logicalAnd[] = $query->equals('section', $section);
        $logicalAnd[] = $query->equals('title', $title);

        $query->matching(
            $query->logicalAnd($logicalAnd)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }
}
