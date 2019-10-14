<?php
namespace PPK\BmfBudgetImportXml\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Tests\UnitTestCase;
use PPK\BmfBudgetImportXml\Domain\Model\Page;

class PagesTest extends UnitTestCase
{
    /**
     * @var \PPK\BmfBudgetImportXml\Domain\Model\Page
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new Page();
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

        $this->subject->setTitle('tested');

        $this->assertAttributeEquals(
            'tested',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDoktypeReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getDoktype()
        );
    }

    /**
     * @test
     */
    public function setDoktypeForIntegerSetsDoktype()
    {

        $this->subject->setDoktype('0');

        $this->assertAttributeEquals(
            0,
            'doktype',
            $this->subject
        );
    }


    /**
     * @test
     */
    public function getTsConfigReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTsConfig()
        );
    }

    /**
     * @test
     */
    public function setTsConfigForStringSetsTsConfig()
    {

        $this->subject->setTsConfig('tested');

        $this->assertAttributeEquals(
            'tested',
            'tsConfig',
            $this->subject
        );
    }


    /**
     * @test
     */
    public function getSortingReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getSorting()
        );
    }

    /**
     * @test
     */
    public function setSortingForIntegerSetsSorting()
    {

        $this->subject->setSorting('0');

        $this->assertAttributeEquals(
            0,
            'sorting',
            $this->subject
        );
    }
}
