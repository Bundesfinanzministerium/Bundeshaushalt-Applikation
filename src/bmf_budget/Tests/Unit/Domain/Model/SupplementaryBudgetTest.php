<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class SupplementaryBudgetTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget();
    }

    protected function tearDown()
    {
        unset($this->subject);
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
    public function getSupplementaryTitlesReturnsInitialValueForSupplementaryTitle()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getSupplementaryTitles()
        );
    }

    /**
     * @test
     */
    public function setSupplementaryTitlesForObjectStorageContainingSupplementaryTitleSetsSupplementaryTitles()
    {
        $supplementaryTitle = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $objectStorageHoldingExactlyOneSupplementaryTitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneSupplementaryTitles->attach($supplementaryTitle);
        $this->subject->setSupplementaryTitles($objectStorageHoldingExactlyOneSupplementaryTitles);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneSupplementaryTitles,
            'supplementaryTitles',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addSupplementaryTitleToObjectStorageHoldingSupplementaryTitles()
    {
        $supplementaryTitle = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $supplementaryTitlesObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $supplementaryTitlesObjectStorageMock->expects(
            $this->once()
        )->method('attach')->with($this->equalTo($supplementaryTitle));
        $this->inject($this->subject, 'supplementaryTitles', $supplementaryTitlesObjectStorageMock);

        $this->subject->addSupplementaryTitle($supplementaryTitle);
    }

    /**
     * @test
     */
    public function removeSupplementaryTitleFromObjectStorageHoldingSupplementaryTitles()
    {
        $supplementaryTitle = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
        $supplementaryTitlesObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $supplementaryTitlesObjectStorageMock->expects(
            $this->once()
        )->method('detach')->with($this->equalTo($supplementaryTitle));
        $this->inject($this->subject, 'supplementaryTitles', $supplementaryTitlesObjectStorageMock);

        $this->subject->removeSupplementaryTitle($supplementaryTitle);
    }
}
