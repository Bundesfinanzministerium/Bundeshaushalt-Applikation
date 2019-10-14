<?php
namespace PPKOELN\BmfBudgetCrawler\Controller\Crawler;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class QueueController extends ActionController
{
    /**
     * Crawler repository
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository
     * @inject
     */
    protected $crawlerRepository;

    /**
     * Main action
     *
     * @return null
     */
    public function indexAction()
    {
        $this->view->assign(
            'crawlers',
            $this->crawlerRepository->findAll()
        );
        return null;
    }
}
