<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class SectionTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Section
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Section();
    }

    protected function tearDown()
    {
        unset($this->subject);
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
    public function getBudgetgroupsReturnsInitialValueForBudgetgroup()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getBudgetgroups()
        );
    }

    /**
     * @test
     */
    public function setBudgetgroupsForObjectStorageContainingBudgetgroupSetsBudgetgroups()
    {
        $budgetgroup = new \PPKOELN\BmfBudget\Domain\Model\Budgetgroup();
        $objectStorageHoldingExactlyOneBudgetgroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneBudgetgroups->attach($budgetgroup);
        $this->subject->setBudgetgroups($objectStorageHoldingExactlyOneBudgetgroups);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneBudgetgroups,
            'budgetgroups',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addBudgetgroupToObjectStorageHoldingBudgetgroups()
    {
        $budgetgroup = new \PPKOELN\BmfBudget\Domain\Model\Budgetgroup();
        $budgetgroupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $budgetgroupsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($budgetgroup));
        $this->inject($this->subject, 'budgetgroups', $budgetgroupsObjectStorageMock);

        $this->subject->addBudgetgroup($budgetgroup);
    }

    /**
     * @test
     */
    public function removeBudgetgroupFromObjectStorageHoldingBudgetgroups()
    {
        $budgetgroup = new \PPKOELN\BmfBudget\Domain\Model\Budgetgroup();
        $budgetgroupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $budgetgroupsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($budgetgroup));
        $this->inject($this->subject, 'budgetgroups', $budgetgroupsObjectStorageMock);

        $this->subject->removeBudgetgroup($budgetgroup);
    }

    /**
     * @test
     */
    public function getTitlegroupsReturnsInitialValueForTitlegroup()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getTitlegroups()
        );
    }

    /**
     * @test
     */
    public function setTitlegroupsForObjectStorageContainingTitlegroupSetsTitlegroups()
    {
        $titlegroup = new \PPKOELN\BmfBudget\Domain\Model\Titlegroup();
        $objectStorageHoldingExactlyOneTitlegroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneTitlegroups->attach($titlegroup);
        $this->subject->setTitlegroups($objectStorageHoldingExactlyOneTitlegroups);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneTitlegroups,
            'titlegroups',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addTitlegroupToObjectStorageHoldingTitlegroups()
    {
        $titlegroup = new \PPKOELN\BmfBudget\Domain\Model\Titlegroup();
        $titlegroupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $titlegroupsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($titlegroup));
        $this->inject($this->subject, 'titlegroups', $titlegroupsObjectStorageMock);

        $this->subject->addTitlegroup($titlegroup);
    }

    /**
     * @test
     */
    public function removeTitlegroupFromObjectStorageHoldingTitlegroups()
    {
        $titlegroup = new \PPKOELN\BmfBudget\Domain\Model\Titlegroup();
        $titlegroupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $titlegroupsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($titlegroup));
        $this->inject($this->subject, 'titlegroups', $titlegroupsObjectStorageMock);

        $this->subject->removeTitlegroup($titlegroup);
    }
}
