<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractValue extends AbstractInfo
{
    /**
     * Actual income property
     *
     * @var float
     */
    protected $actualIncome;

    /**
     * Actual expenses property
     *
     * @var float
     */
    protected $actualExpenses;

    /**
     * Target income property
     *
     * @var float
     */
    protected $targetIncome;

    /**
     * Target expenses property
     *
     * @var float
     */
    protected $targetExpenses;

    /**
     * Returns the actual income
     *
     * @return float $actualIncome
     */
    public function getActualIncome()
    {
        return $this->actualIncome;
    }

    /**
     * Sets the actual income
     *
     * @param float $actualIncome The actual income to be added
     *
     * @return void
     */
    public function setActualIncome($actualIncome)
    {
        $this->actualIncome = $actualIncome;
    }

    /**
     * Returns the actual expenses
     *
     * @return float $actualExpenses
     */
    public function getActualExpenses()
    {
        return $this->actualExpenses;
    }

    /**
     * Sets the actual expenses
     *
     * @param float $actualExpenses The actual expenses to be added
     *
     * @return void
     */
    public function setActualExpenses($actualExpenses)
    {
        $this->actualExpenses = $actualExpenses;
    }

    /**
     * Returns the target Income
     *
     * @return float $targetIncome
     */
    public function getTargetIncome()
    {
        return $this->targetIncome;
    }

    /**
     * Sets the target income
     *
     * @param float $targetIncome The target income to be added
     *
     * @return void
     */
    public function setTargetIncome($targetIncome)
    {
        $this->targetIncome = $targetIncome;
    }

    /**
     * Returns the target expenses
     *
     * @return float $targetExpenses
     */
    public function getTargetExpenses()
    {
        return $this->targetExpenses;
    }

    /**
     * Sets the target expenses
     *
     * @param float $targetExpenses The target expenses to be added
     *
     * @return void
     */
    public function setTargetExpenses($targetExpenses)
    {
        $this->targetExpenses = $targetExpenses;
    }

    /**
     * @param string $account
     * @param string $flow
     *
     * @return bool
     */
    public function getRevenue($account = '', $flow = '')
    {

        $account = strtolower($account);
        $flow = ucfirst($flow);

        return isset($this->{$account . $flow})
            ? $this->{$account . $flow} === null ?: $this->{$account . $flow}
            : false;
    }
}
