<?php
namespace PPK\BmfBudgetImportXml\Tests\Unit\Controller;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use PPK\BmfBudgetImportXml\Controller\BackendController;

class BackendControllerTest extends UnitTestCase
{
    /**
     * @var \PPK\BmfBudgetImportXml\Controller\BackendController
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = $this->getMock(
            BackendController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
    }

    public function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function getIndexAndAssignsItToView()
    {
        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->fixture, 'view', $view);
        $this->assertSame(null, $this->fixture->indexAction());
    }
}
