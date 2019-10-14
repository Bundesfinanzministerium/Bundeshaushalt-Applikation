<?php
namespace PPK\BmfBudgetImportXml\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Tests\UnitTestCase;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class FileDtoTest extends UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudgetImportExcel\Domain\Model\Dto\FileDto
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new FileDto();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForInteger()
    {
        $this->assertSame(
            null,
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForIntegerSetsName()
    {

        $this->subject->setName(null);

        $this->assertAttributeEquals(
            null,
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForInteger()
    {
        $this->assertSame(
            null,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeForIntegerSetsType()
    {
        $this->subject->setType(null);

        $this->assertAttributeEquals(
            null,
            'type',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTmpNameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTmpName()
        );
    }

    /**
     * @test
     */
    public function setTmpNameForStringSetsTmpName()
    {
        $this->subject->setTmp_name('tested');

        $this->assertAttributeEquals(
            'tested',
            'tmp_name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getErrorReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getError()
        );
    }

    /**
     * @test
     */
    public function setErrorForIntegerSetsError()
    {
        $this->subject->setError('0');

        $this->assertAttributeEquals(
            0,
            'error',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSizeReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getSize()
        );
    }

    /**
     * @test
     */
    public function setSizeForIntegerSetsSize()
    {
        $this->subject->setSize('0');

        $this->assertAttributeEquals(
            0,
            'size',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAbsoluteFilenameReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAbsoluteFilename()
        );
    }

    /**
     * @test
     */
    public function setAbsoluteFilenameForStringSetsAbsoluteFilename()
    {
        $this->subject->setAbsoluteFilename('tested');

        $this->assertAttributeEquals(
            'tested',
            'absoluteFilename',
            $this->subject
        );
    }
}
