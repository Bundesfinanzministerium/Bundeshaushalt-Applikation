<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Repository;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\Persistence\Repository;

class CrawlerRepository extends Repository
{
    /**
     * Find in Job
     *
     * @return \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     */
    public function findInprogressJob()
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->setLimit(1);
        $query->setOrderings(['uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);
        $query->matching(
            $query->lessThan('progress', 100)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }
}
