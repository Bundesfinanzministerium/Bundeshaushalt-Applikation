<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPKOELN\BmfBudget\Domain\Model\Budget;

class SupplementaryDto
{
    /**
     * Budget
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     * @validate NotEmpty
     */
    protected $budget;

    /**
     * Budget
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     * @validate NotEmpty
     */
    protected $supplementary;

    /**
     * Return the Budget
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets the Budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     *
     * @return void
     */
    public function setBudget(Budget $budget = null)
    {
        $this->budget = $budget;
    }

    /**
     * Return the supplementary budget
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     */
    public function getSupplementary()
    {
        return $this->supplementary;
    }

    /**
     * Sets the supplementary budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementary
     *
     * @return void
     */
    public function setSupplementary($supplementary)
    {
        $this->supplementary = $supplementary;
    }
}
