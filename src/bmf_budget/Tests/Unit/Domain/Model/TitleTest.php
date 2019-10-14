<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TitleTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Title
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Title();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFlowReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getFlow()
        );
    }

    /**
     * @test
     */
    public function setFlowForIntegerSetsFlow()
    {
        $this->subject->setFlow(12);

        $this->assertAttributeEquals(
            12,
            'flow',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFlexibleReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            false,
            $this->subject->getFlexible()
        );
    }

    /**
     * @test
     */
    public function setFlexibleForBooleanSetsFlexible()
    {
        $this->subject->setFlexible(true);

        $this->assertAttributeEquals(
            true,
            'flexible',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getActualPageReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getActualPage()
        );
    }

    /**
     * @test
     */
    public function setActualPageForIntegerSetsActualPage()
    {
        $this->subject->setActualPage(12);

        $this->assertAttributeEquals(
            12,
            'actualPage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTargetPageReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getTargetPage()
        );
    }

    /**
     * @test
     */
    public function setTargetPageForIntegerSetsActualPage()
    {
        $this->subject->setTargetPage(12);

        $this->assertAttributeEquals(
            12,
            'targetPage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getBudgetsReturnsInitialValueForBudget()
    {
        $this->assertEquals(
            null,
            $this->subject->getBudget()
        );
    }

    /**
     * @test
     */
    public function setBudgetsForBudgetSetsBudgets()
    {
        $budgetsFixture = new \PPKOELN\BmfBudget\Domain\Model\Budget();
        $this->subject->setBudget($budgetsFixture);

        $this->assertAttributeEquals(
            $budgetsFixture,
            'budget',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSupplementariesReturnsInitialValueForSupplementaryTitle()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getSupplementaries()
        );
    }

    /**
     * @test
     */
    public function setSupplementariesForObjectStorageContainingSupplementaryTitleSetsSupplementaries()
    {
        $supplementary = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $objectStorageHoldingExactlyOneSupplementaries = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneSupplementaries->attach($supplementary);
        $this->subject->setSupplementaries($objectStorageHoldingExactlyOneSupplementaries);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneSupplementaries,
            'supplementaries',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addSupplementaryToObjectStorageHoldingSupplementaries()
    {
        $supplementary = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $supplementariesObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $supplementariesObjectStorageMock->expects(
            $this->once()
        )->method('attach')->with($this->equalTo($supplementary));
        $this->inject($this->subject, 'supplementaries', $supplementariesObjectStorageMock);

        $this->subject->addSupplementary($supplementary);
    }

    /**
     * @test
     */
    public function removeSupplementaryFromObjectStorageHoldingSupplementaries()
    {
        $supplementary = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $supplementariesObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $supplementariesObjectStorageMock->expects(
            $this->once()
        )->method('detach')->with($this->equalTo($supplementary));
        $this->inject($this->subject, 'supplementaries', $supplementariesObjectStorageMock);

        $this->subject->removeSupplementary($supplementary);
    }
}
