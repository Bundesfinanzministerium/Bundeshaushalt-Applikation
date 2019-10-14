<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TitlegroupTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Titlegroup
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Titlegroup();
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
    public function getTitlesReturnsInitialValueForTitle()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getTitles()
        );
    }

    /**
     * @test
     */
    public function setTitlesForObjectStorageContainingTitleSetsTitles()
    {
        $title = new \PPKOELN\BmfBudget\Domain\Model\Title();
        $objectStorageHoldingExactlyOneTitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneTitles->attach($title);
        $this->subject->setTitles($objectStorageHoldingExactlyOneTitles);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneTitles,
            'titles',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addTitleToObjectStorageHoldingTitles()
    {
        $title = new \PPKOELN\BmfBudget\Domain\Model\Title();
        $titlesObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $titlesObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($title));
        $this->inject($this->subject, 'titles', $titlesObjectStorageMock);

        $this->subject->addTitle($title);
    }

    /**
     * @test
     */
    public function removeTitleFromObjectStorageHoldingTitles()
    {
        $title = new \PPKOELN\BmfBudget\Domain\Model\Title();
        $titlesObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $titlesObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($title));
        $this->inject($this->subject, 'titles', $titlesObjectStorageMock);

        $this->subject->removeTitle($title);
    }
}
