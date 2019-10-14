<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Controller;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPKOELN\BmfBudget\Domain\Repository\BudgetRepository;
use PPKOELN\BmfBudget\Service\Page\TitleService;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use PPKOELN\BmfBudget\Controller\BudgetController;

class BudgetControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PPKOELN\BmfBudget\Controller\BudgetController
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = $this->getMock(
            BudgetController::class,
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
    public function showActionShouldForwardToIntroIfNoValidBudgetIsPresent()
    {

        /** @var \PHPUnit_Framework_MockObject_MockObject $queryResultStub */
        $queryResultStub = $this->getMockBuilder(QueryResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $queryResultStub->method('getFirst')->willReturn(false);

        // stubbing servicePageTitle
        $servicePageTitleStub = $this->getMock(TitleService::class, ['set'], [], '', false);
        $this->inject($this->subject, 'servicePageTitle', $servicePageTitleStub);

        // mocking budget repository
        $budgetRepository = $this->getMock(BudgetRepository::class, ['findByYear'], [], '', false);
        $budgetRepository->expects($this->once())->method('findByYear')->will($this->returnValue($queryResultStub));
        $this->inject($this->subject, 'budgetRepository', $budgetRepository);

        $this->subject->expects($this->once())->method('forward');
        $this->subject->showAction();
    }
}
