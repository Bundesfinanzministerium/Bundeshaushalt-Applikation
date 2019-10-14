<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class BudgetCrawlerDto
{
    /**
     * Hook
     *
     * @var Int
     * @validate NotEmpty
     */
    protected $hook;

    /**
     * Budget
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     * @validate NotEmpty
     */
    protected $budget;

    /**
     * Account
     * Possible values are 'target', 'actual'
     *
     * @var array
     */
    protected $account = [];

    /**
     * Flow
     * Possible values are 'income', 'expenses'
     *
     * @var array
     */
    protected $flow = [];

    /**
     * Strucure
     * Possible values are 'section', 'function', 'group'
     *
     * @var array
     */
    protected $structure = [];

    /**
     * Session
     *
     * @var string
     */
    protected $session = '';

    /**
     * __construct
     */
    public function __construct()
    {
        $this->session = md5(time());
    }

    /**
     * @return int
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @param int $hook
     */
    public function setHook($hook)
    {
        $this->hook = $hook;
    }

    /**
     * @return \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @return array
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param array $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return array
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * @param array $flow
     */
    public function setFlow($flow)
    {
        $this->flow = $flow;
    }

    /**
     * @return array
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param array $structure
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}
