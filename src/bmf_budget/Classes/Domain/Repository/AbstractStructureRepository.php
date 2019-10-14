<?php
namespace PPKOELN\BmfBudget\Domain\Repository;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PPKOELN\BmfBudget\Domain\Model\AbstractStructure;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use PPKOELN\BmfBudget\Domain\Model\Functione;
use PPKOELN\BmfBudget\Domain\Model\Groupe;
use PPKOELN\BmfBudget\Domain\Model\Section;

class AbstractStructureRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Find Object by given Haushaltsplan and Address
     *
     * @param AbstractStructure|null $entity
     * @param string $address Corresponding address
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByEntityAndAddress(AbstractStructure $entity = null, $address = '')
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) {
            // cli currently ignores recursive parameter
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        $logicalAnd = [];
        $logicalAnd[] = $query->equals('address', $address);

        if ($entity instanceof Section) {
            $logicalAnd[] = $query->equals('section', $entity);
        } elseif ($entity instanceof Groupe) {
            $logicalAnd[] = $query->equals('groupe', $entity);
        } elseif ($entity instanceof Functione) {
            $logicalAnd[] = $query->equals('functione', $entity);
        } else {
            return false;
        }

        $query->matching(
            $query->logicalAnd($logicalAnd)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }

    /**
     * Find Object by given Haushaltsplan and Address
     *
     * @param Budget $budget Corresponding object
     * @param string $address Corresponding address
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByBudgetAndAddress(Budget $budget = null, $address = '')
    {
        $length = [];

        if ($this instanceof SectionRepository) {
            $length = [2, 4];
        } elseif ($this instanceof GroupeRepository) {
            $length = [1, 2, 3];
        } elseif ($this instanceof FunctioneRepository) {
            $length = [1, 2, 3];
        } elseif ($this instanceof TitleRepository) {
            $length = [9];
        }

        if (strlen($address) > $length[0]) {
            /*
             * The address length is greater than the address of the first level entity
             * example:
             *        first level element is section 01 or 60 or ...
             *        current is 0102 or 6010
             * this address refers to an parent section entity not to a budget, so
             * we need an extended method
             */
            $retVal = $this->findByBudgetAndAddressBasedOnEntity($budget, $address, $length);
        } else {
            /*
             * The address length is equal the first level element
             * so a direct query is possible
             */

            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
            $query = $this->createQuery();
            if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) {
                // cli currently ignores recursive parameter
                $query->getQuerySettings()->setRespectStoragePage(false);
            }

            $logicalAnd = [];
            $logicalAnd[] = $query->equals('budget', $budget);
            $logicalAnd[] = $query->equals('address', $address);

            $query->matching(
                $query->logicalAnd($logicalAnd)
            );

            /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
            $result = $query->execute();
            $retVal = count($result) > 0 ? $result->getFirst() : false;
        }
        return $retVal;
    }

    /**
     * If the address doesn't refer on a budget
     *
     * Current structure entities can refer to budgets OR parent entities
     * This function will be used if the address refers to an entity parrent
     * like : section 6002 (refers to 60 as parent)
     *
     * If this happens we have to find the budget and iterate to the entity
     *
     * @param $budget
     * @param $address
     * @param $length
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByBudgetAndAddressBasedOnEntity($budget, $address, $length)
    {
        // first step: getting corresponding budget by reducing the address to the first level
        $index = array_shift($length);

        if (!($entity = $this->findByBudgetAndAddress($budget, substr($address, 0, $index)))) {
            // return null if entity is false
            return null;
        }
        $result = $entity;

        foreach ($length as $index) {
            if ($index <= strlen($address)) {
                if (!$entity = $this->findByEntityAndAddress($entity, substr($address, 0, $index))) {
                    $result = null;
                    break;
                } else {
                    $result = $entity;
                }
            }
        }
        return $result;
    }

    /**
     * Find Object by given Haushaltsplan and Title
     *
     * @param Budget $budget Corresponding object
     * @param string $title Corresponding title
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function findByBudgetAndTitle(Budget $budget = null, $title = '')
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) {
            // cli currently ignores recursive parameter
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        $logicalAnd = [];
        $logicalAnd[] = $query->equals('budget', $budget);
        $logicalAnd[] = $query->equals('title', $title);

        $query->matching(
            $query->logicalAnd($logicalAnd)
        );

        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $result */
        $result = $query->execute();

        return count($result) > 0 ? $result->getFirst() : false;
    }
}
