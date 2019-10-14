<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TimelineCrawlerDto
{
    /**
     * Hook
     *
     * @var Int
     * @validate NotEmpty
     */
    protected $hook;

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
