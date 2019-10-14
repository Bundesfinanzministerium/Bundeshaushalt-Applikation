<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Controller\Backend\Maintenance;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use PPKOELN\BmfBudget\Domain\Repository\BudgetRepository;
use PPKOELN\BmfBudget\Service\Backend\Totaling\GroupService;
use PPKOELN\BmfBudget\Service\Backend\Totaling\FunctionService;
use PPKOELN\BmfBudget\Service\Backend\Totaling\SectionService;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use PPKOELN\BmfBudget\Controller\Backend\Maintenance\TotallingController;

class TotallingControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PPKOELN\BmfBudget\Controller\BudgetController
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = $this->getMock(
            TotallingController::class,
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
    public function indexActionShouldAlwaysAssignBudgets()
    {

        $allBudgets = $this->getMock(ObjectStorage::class, [], [], '', false);

        $budgetRepository = $this->getMock(BudgetRepository::class, ['findAll'], [], '', false);
        $budgetRepository->expects($this->once())->method('findAll')->will($this->returnValue($allBudgets));
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $view = $this->getMock(ViewInterface::class);
        $view->expects($this->once())->method('assign')->with('budgets', $allBudgets);
        $this->inject($this->subject, 'view', $view);

        $this->subject->indexAction();
    }

    /**
     * @test
     */
    public function indexActionShouldCallSectionServiceIfSelected()
    {

        $budgetRepository = $this->getMock(BudgetRepository::class, ['findAll'], [], '', false);
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);

        $totallingform = new \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto();
        $totallingform->setStructure('section');

        // \PPKOELN\BmfBudget\Service\Backend\Totaling\SectionService
        $sectionService = $this->getMock(SectionService::class, ['process'], [], '', false);
        $sectionService->expects($this->once())->method('process')->will($this->returnValue(true));
        $this->inject($this->subject, 'serviceBackendTotalingSection', $sectionService);

        $this->subject->indexAction($totallingform);
    }

    /**
     * @test
     */
    public function indexActionShouldCallFunctionServiceIfSelected()
    {

        $budgetRepository = $this->getMock(BudgetRepository::class, ['findAll'], [], '', false);
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);

        $totallingform = new \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto();
        $totallingform->setStructure('function');

        $functionService = $this->getMock(FunctionService::class, ['process'], [], '', false);
        $functionService->expects($this->once())->method('process')->will($this->returnValue(true));
        $this->inject($this->subject, 'serviceBackendTotalingFunction', $functionService);

        $this->subject->indexAction($totallingform);
    }

    /**
     * @test
     */
    public function indexActionShouldCallGroupServiceIfSelected()
    {

        $budgetRepository = $this->getMock(BudgetRepository::class, ['findAll'], [], '', false);
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);

        $totallingform = new \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto();
        $totallingform->setStructure('group');

        $groupService = $this->getMock(GroupService::class, ['process'], [], '', false);
        $groupService->expects($this->once())->method('process')->will($this->returnValue(true));
        $this->inject($this->subject, 'serviceBackendTotalingGroup', $groupService);

        $this->subject->indexAction($totallingform);
    }

    /**
     * @test
     */
    public function indexActionShouldAssignFalseAsTotalsIfNoStrucureIsValid()
    {

        $budgetRepository = $this->getMock(BudgetRepository::class, ['findAll'], [], '', false);
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $totallingform = new \PPKOELN\BmfBudget\Domain\Model\Backend\Maintenance\Dto\TotallingDto();
        $totallingform->setStructure('blaaaa');

        $view = $this->getMock(ViewInterface::class);
        $view->expects($this->at(2))->method('assign')->with('totals', false);
        $this->inject($this->subject, 'view', $view);

        $this->subject->indexAction($totallingform);
    }
}
