<?php
namespace PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TotallingDto
{

    /**
     * Budget property
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     * @validate NotEmpty
     */
    protected $budget;

    /**
     * Structure property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $structure = '';

    /**
     * Returns the budget entity
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Budget $budgets
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets the budget entity
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget The budget entity to be set
     *
     * @return void
     */
    public function setBudget(\PPKOELN\BmfBudget\Domain\Model\Budget $budget = null)
    {
        $this->budget = $budget;
    }

    /**
     * Returns the structure
     *
     * @return string $structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Sets the structure
     *
     * @param string $structure The structure entity to be set
     *
     * @return void
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }
}
