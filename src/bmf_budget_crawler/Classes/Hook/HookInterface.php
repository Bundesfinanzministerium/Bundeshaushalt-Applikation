<?php
namespace PPKOELN\BmfBudgetCrawler\Hook;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

interface HookInterface
{
    public function getTitle();

    public function preProc(\PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler);

    public function proc(\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue &$queue, &$result, &$error);
}
