<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Repository;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\Persistence\Repository;

class QueueRepository extends Repository
{

    /**
     * Find queues by crawler
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @param int $limit
     * @return object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByCrawler(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler,
        $limit = 0
    ) {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->setLimit($limit);
        $query->setOrderings(['uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);
        $query->matching(
            $query->logicalAnd(
                $query->equals('crawler', $crawler),
                $query->equals('status', 0)
            )
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result : false;
    }

    /**
     * Count crawled queries
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function countCrawled(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching(
            $query->logicalAnd(
                [
                    $query->equals('crawler', $crawler),
                    $query->greaterThan('status', 0)
                ]
            )
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute()->count();

        return $result;
    }

    /**
     * Count queries by crawler
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     */
    public function countByCrawler(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching(
            $query->equals('crawler', $crawler)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute()->count();

        return $result;
    }
}
