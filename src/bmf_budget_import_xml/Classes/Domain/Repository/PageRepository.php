<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

class PageRepository extends Repository
{
    /**
     * Find Object by given Section and Address
     *
     * @param string $title
     * @param int $pageId
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByTitleAndPid($title = '', $pageId = 0)
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        $logicalAnd = [];
        $logicalAnd[] = $query->equals('title', $title);
        $logicalAnd[] = $query->equals('pid', $pageId);

        $query->matching(
            $query->logicalAnd($logicalAnd)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }

    /**
     * Find Object by given Section and Address
     *
     * @param int $pageId
     * @return bool|object
     */
    public function findLastEntityByPid($pageId = 0)
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->setOrderings(
            [
            'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            ]
        );
        $query->setLimit(1);

        $query->matching(
            $query->equals('pid', $pageId)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }
}
