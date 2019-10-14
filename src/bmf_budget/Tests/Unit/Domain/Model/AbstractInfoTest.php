<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractInfoTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\AbstractInfo
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\AbstractInfo();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getInfoImageReturnsInitialValueForFileReference()
    {
        $this->assertEquals(
            null,
            $this->subject->getInfoImage()
        );
    }

    /**
     * @test
     */
    public function setInfoImageForFileReferenceSetsInfoImage()
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setInfoImage($fileReferenceFixture);

        $this->assertAttributeEquals(
            $fileReferenceFixture,
            'infoImage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getInfoTextReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getInfoText()
        );
    }

    /**
     * @test
     */
    public function setInfoTextForStringSetsInfoText()
    {
        $this->subject->setInfoText('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'infoText',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getInfoLinkReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getInfoLink()
        );
    }

    /**
     * @test
     */
    public function setInfoLinkForStringSetsInfoLink()
    {
        $this->subject->setInfoLink('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'infoLink',
            $this->subject
        );
    }
}
