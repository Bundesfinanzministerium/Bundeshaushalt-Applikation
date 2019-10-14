<?php
namespace PPKOELN\BmfBudgetCrawler\Tests\Unit\Controller;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use PPKOELN\BmfBudgetCrawler\Controller\BackendController;

/**
 * Test case for class PPKOELN\BmfBudgetCrawler\Controller\CrawlerController.
 */
class CrawlerControllerTest extends UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudgetCrawler\Controller\BackendController
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = $this->getMock(
            BackendController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getIndexAndAssignsItToView()
    {
        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $this->assertSame(
            null,
            $this->subject->indexAction()
        );
    }
}
