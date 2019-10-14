<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Controller\Backend;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPKOELN\BmfBudget\Controller\Backend\MaintenanceController;

class MaintenanceControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PPKOELN\BmfBudget\Controller\BudgetController
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = $this->getMock(
            MaintenanceController::class,
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
    public function indexActionShouldOnlyReturnNull()
    {

        $this->assertNull(
            $this->subject->indexAction(),
            'shoul return NULL'
        );
    }
}
