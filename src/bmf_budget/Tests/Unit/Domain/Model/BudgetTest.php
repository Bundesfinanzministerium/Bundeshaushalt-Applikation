<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class BudgetTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Budget();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTitleActualReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitleActual()
        );
    }

    /**
     * @test
     */
    public function setTitleActualForStringSetsTitle()
    {
        $this->subject->setTitleActual('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'titleActual',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTitleTargetReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitleTarget()
        );
    }

    /**
     * @test
     */
    public function setTitleTargetForStringSetsTitle()
    {
        $this->subject->setTitleTarget('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'titleTarget',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getYearReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getYear()
        );
    }

    /**
     * @test
     */
    public function setYearForStringSetsYear()
    {
        $this->subject->setYear('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'year',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSectionActualIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getSectionActualIncome()
        );
    }

    /**
     * @test
     */
    public function setSectionActualIncomeForFloatSetsSectionActualIncome()
    {
        $this->subject->setSectionActualIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'sectionActualIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getSectionActualExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getSectionActualExpenses()
        );
    }

    /**
     * @test
     */
    public function setSectionActualExpensesForFloatSetsSectionActualExpenses()
    {
        $this->subject->setSectionActualExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'sectionActualExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getSectionTargetIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getSectionTargetIncome()
        );
    }

    /**
     * @test
     */
    public function setSectionTargetIncomeForFloatSetsSectionTargetIncome()
    {
        $this->subject->setSectionTargetIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'sectionTargetIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getSectionTargetExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getSectionTargetExpenses()
        );
    }

    /**
     * @test
     */
    public function setSectionTargetExpensesForFloatSetsSectionTargetExpenses()
    {
        $this->subject->setSectionTargetExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'sectionTargetExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getFunctionActualIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getFunctionActualIncome()
        );
    }

    /**
     * @test
     */
    public function setFunctionActualIncomeForFloatSetsFunctionActualIncome()
    {
        $this->subject->setFunctionActualIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'functionActualIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getFunctionActualExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getFunctionActualExpenses()
        );
    }

    /**
     * @test
     */
    public function setFunctionActualExpensesForFloatSetsFunctionActualExpenses()
    {
        $this->subject->setFunctionActualExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'functionActualExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getFunctionTargetIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getFunctionTargetIncome()
        );
    }

    /**
     * @test
     */
    public function setFunctionTargetIncomeForFloatSetsFunctionTargetIncome()
    {
        $this->subject->setFunctionTargetIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'functionTargetIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getFunctionTargetExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getFunctionTargetExpenses()
        );
    }

    /**
     * @test
     */
    public function setFunctionTargetExpensesForFloatSetsFunctionTargetExpenses()
    {
        $this->subject->setFunctionTargetExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'functionTargetExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getGroupActualIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getGroupActualIncome()
        );
    }

    /**
     * @test
     */
    public function setGroupActualIncomeForFloatSetsGroupActualIncome()
    {
        $this->subject->setGroupActualIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'groupActualIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getGroupActualExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getGroupActualExpenses()
        );
    }

    /**
     * @test
     */
    public function setGroupActualExpensesForFloatSetsGroupActualExpenses()
    {
        $this->subject->setGroupActualExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'groupActualExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getGroupTargetIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getGroupTargetIncome()
        );
    }

    /**
     * @test
     */
    public function setGroupTargetIncomeForFloatSetsGroupTargetIncome()
    {
        $this->subject->setGroupTargetIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'groupTargetIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getGroupTargetExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getGroupTargetExpenses()
        );
    }

    /**
     * @test
     */
    public function setGroupTargetExpensesForFloatSetsGroupTargetExpenses()
    {
        $this->subject->setGroupTargetExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'groupTargetExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getPidTitleReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidTitle()
        );
    }

    /**
     * @test
     */
    public function setPidTitleForIntegerSetsPidTitle()
    {
        $this->subject->setPidTitle(12);

        $this->assertAttributeEquals(
            12,
            'pidTitle',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidSectionReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidSection()
        );
    }

    /**
     * @test
     */
    public function setPidSectionForIntegerSetsPidSection()
    {
        $this->subject->setPidSection(12);

        $this->assertAttributeEquals(
            12,
            'pidSection',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidFunctionReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidFunction()
        );
    }

    /**
     * @test
     */
    public function setPidFunctionForIntegerSetsPidFunction()
    {
        $this->subject->setPidFunction(12);

        $this->assertAttributeEquals(
            12,
            'pidFunction',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidGroupReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidGroup()
        );
    }

    /**
     * @test
     */
    public function setPidGroupForIntegerSetsPidGroup()
    {
        $this->subject->setPidGroup(12);

        $this->assertAttributeEquals(
            12,
            'pidGroup',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidBudgetgroupReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidBudgetgroup()
        );
    }

    /**
     * @test
     */
    public function setPidBudgetgroupForIntegerSetsPidBudgetgroup()
    {
        $this->subject->setPidBudgetgroup(12);

        $this->assertAttributeEquals(
            12,
            'pidBudgetgroup',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidTitlegroupReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidTitlegroup()
        );
    }

    /**
     * @test
     */
    public function setPidTitlegroupForIntegerSetsPidTitlegroup()
    {
        $this->subject->setPidTitlegroup(12);

        $this->assertAttributeEquals(
            12,
            'pidTitlegroup',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidSupplementaryBudgetReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidSupplementaryBudget()
        );
    }

    /**
     * @test
     */
    public function setPidSupplementaryBudgetForIntegerSetsPidSupplementaryBudget()
    {
        $this->subject->setPidSupplementaryBudget(12);

        $this->assertAttributeEquals(
            12,
            'pidSupplementaryBudget',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getPidSupplementaryTitleReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getPidSupplementaryTitle()
        );
    }

    /**
     * @test
     */
    public function setPidSupplementaryTitleForIntegerSetsPidSupplementaryTitle()
    {
        $this->subject->setPidSupplementaryTitle(12);

        $this->assertAttributeEquals(
            12,
            'pidSupplementaryTitle',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSectionsReturnsInitialValueForSection()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getSections()
        );
    }

    /**
     * @test
     */
    public function setSectionsForObjectStorageContainingSectionSetsSections()
    {
        $section = new \PPKOELN\BmfBudget\Domain\Model\Section();
        $objectStorageHoldingExactlyOneSections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneSections->attach($section);
        $this->subject->setSections($objectStorageHoldingExactlyOneSections);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneSections,
            'sections',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addSectionToObjectStorageHoldingSections()
    {
        $section = new \PPKOELN\BmfBudget\Domain\Model\Section();
        $sectionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $sectionsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($section));
        $this->inject($this->subject, 'sections', $sectionsObjectStorageMock);

        $this->subject->addSection($section);
    }

    /**
     * @test
     */
    public function removeSectionFromObjectStorageHoldingSections()
    {
        $section = new \PPKOELN\BmfBudget\Domain\Model\Section();
        $sectionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $sectionsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($section));
        $this->inject($this->subject, 'sections', $sectionsObjectStorageMock);

        $this->subject->removeSection($section);
    }

    /**
     * @test
     */
    public function getGroupsReturnsInitialValueForGroupe()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getGroups()
        );
    }

    /**
     * @test
     */
    public function setGroupsForObjectStorageContainingGroupeSetsGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $objectStorageHoldingExactlyOneGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneGroups->attach($group);
        $this->subject->setGroups($objectStorageHoldingExactlyOneGroups);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneGroups,
            'groups',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addGroupToObjectStorageHoldingGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $groupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $groupsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($group));
        $this->inject($this->subject, 'groups', $groupsObjectStorageMock);

        $this->subject->addGroup($group);
    }

    /**
     * @test
     */
    public function removeGroupFromObjectStorageHoldingGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $groupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $groupsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($group));
        $this->inject($this->subject, 'groups', $groupsObjectStorageMock);

        $this->subject->removeGroup($group);
    }

    /**
     * @test
     */
    public function getFunctionsReturnsInitialValueForFunction()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getFunctions()
        );
    }

    /**
     * @test
     */
    public function setFunctionsForObjectStorageContainingFunctionSetsFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $objectStorageHoldingExactlyOneFunctions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneFunctions->attach($function);
        $this->subject->setFunctions($objectStorageHoldingExactlyOneFunctions);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneFunctions,
            'functions',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addFunctionToObjectStorageHoldingFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $functionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $functionsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($function));
        $this->inject($this->subject, 'functions', $functionsObjectStorageMock);

        $this->subject->addFunction($function);
    }

    /**
     * @test
     */
    public function removeFunctionFromObjectStorageHoldingFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $functionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $functionsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($function));
        $this->inject($this->subject, 'functions', $functionsObjectStorageMock);

        $this->subject->removeFunction($function);
    }

    /**
     * @test
     */
    public function getSupplementaryBudgetsReturnsInitialValueForSupplementaryBudget()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getSupplementaryBudgets()
        );
    }

    /**
     * @test
     */
    public function setSupplementaryBudgetsForObjectStorageContainingSupplementaryBudgetSetsSupplementaryBudgets()
    {
        $supplementaryBudget = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget();
        $objectStorageHoldingExactlyOneSupplementaryBudgets = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneSupplementaryBudgets->attach($supplementaryBudget);
        $this->subject->setSupplementaryBudgets($objectStorageHoldingExactlyOneSupplementaryBudgets);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneSupplementaryBudgets,
            'supplementaryBudgets',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addSupplementaryBudgetToObjectStorageHoldingSupplementaryBudgets()
    {
        $supplementaryBudget = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget();
        $supplementaryBudgetsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $supplementaryBudgetsObjectStorageMock->expects(
            $this->once()
        )->method('attach')->with($this->equalTo($supplementaryBudget));
        $this->inject($this->subject, 'supplementaryBudgets', $supplementaryBudgetsObjectStorageMock);

        $this->subject->addSupplementaryBudget($supplementaryBudget);
    }

    /**
     * @test
     */
    public function removeSupplementaryBudgetFromObjectStorageHoldingSupplementaryBudgets()
    {
        $supplementaryBudget = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget();
        $supplementaryBudgetsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $supplementaryBudgetsObjectStorageMock->expects(
            $this->once()
        )->method('detach')->with($this->equalTo($supplementaryBudget));
        $this->inject($this->subject, 'supplementaryBudgets', $supplementaryBudgetsObjectStorageMock);

        $this->subject->removeSupplementaryBudget($supplementaryBudget);
    }
}
