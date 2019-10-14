<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPKOELN\BmfBudget\Domain\Model\Budget;

class BudgetDto
{
    /**
     * Budget
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     * @validate NotEmpty
     */
    protected $budget;

    /**
     * Page offset value
     *
     * This value is needed for calculating the provides section - pdf - page
     *
     * The xml only contains the page of the main document (0-1714)
     * We provide splitted section-pdf's (01 - 60). All section pdf start with page no. 1
     * So we need to calculate: xml page (1750) - pageOffset (e.g.: 1500)
     *
     * @var int
     */
    protected $pageOffset = 0;

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
     * Return the page offset
     *
     * @return int
     */
    public function getPageOffset()
    {
        return $this->pageOffset;
    }

    /**
     * Sets the page offset
     *
     * @param int $pageOffset
     */
    public function setPageOffset($pageOffset)
    {
        $this->pageOffset = $pageOffset;
    }
}
