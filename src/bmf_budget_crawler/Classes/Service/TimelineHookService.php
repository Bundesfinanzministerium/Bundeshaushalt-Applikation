<?php
namespace PPKOELN\BmfBudgetCrawler\Service;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TimelineHookService extends AbstractHookService
{
    public function __construct()
    {
        $this->service = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['bmf_budget_crawler']['crawler']['timelineService'];
    }
}
