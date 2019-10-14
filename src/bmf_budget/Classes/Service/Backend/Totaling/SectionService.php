<?php
namespace PPKOELN\BmfBudget\Service\Backend\Totaling;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class SectionService extends AbstractStructureService
{
    /**
     * Main method
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget Corresponding budget
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function process(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget = null
    ) {
        /**
         * Recordset of child section
         *
         * @var \TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage $storage
         */
        $storage = $budget->getSections();

        $totals = $this->iterateEntities(
            $storage,
            $this->sectionRepository,
            'getSections'
        );

        $budget->setSectionTargetIncome($totals['income']['target']);
        $budget->setSectionActualIncome($totals['income']['actual']);

        $budget->setSectionTargetExpenses($totals['expenses']['target']);
        $budget->setSectionActualExpenses($totals['expenses']['actual']);

        $this->budgetRepository->update($budget);

        $this->persistenceManager->persistAll();

        return $totals;
    }
}
