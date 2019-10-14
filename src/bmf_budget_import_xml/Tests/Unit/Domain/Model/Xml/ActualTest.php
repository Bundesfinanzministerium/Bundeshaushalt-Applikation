<?php
namespace PPK\BmfBudgetImportXml\Tests\Unit\Domain\Model\Xml;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Tests\UnitTestCase;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class ActualTest extends UnitTestCase
{
    /**
     * @var \PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual
     */
    protected $subject;

    protected $fixturefile = 'typo3conf/ext/bmf_budget_import_xml/Tests/Unit/Fixtures/import-fixture-actual.xml';

    protected function setUp()
    {
        $this->subject = new Actual($this->setupFixtureFile());
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Setup fixture object
     *
     * @return FileDto
     */
    protected function setupFixtureFile()
    {
        // prepare absolute filename
        $absoluteFilename = PATH_site . $this->fixturefile;

        $file = new FileDto();
        $file->setName('import-fixture-actual.xml');
        $file->setType('text/xml');
        $file->setTmp_name('tmp/thisisatempname');
        $file->setError(0);
        $file->setSize(filesize($absoluteFilename));
        $file->setAbsoluteFilename($absoluteFilename);

        return $file;
    }

    /**
     * @test
     */
    public function getSectionsShouldReturnAnArrayContainingAllSectionLabels()
    {
        $this->assertSame(
            ['01', '04', '60'],
            $this->subject->getSections()
        );
    }

    /**
     * @test
     */
    public function getSectionShouldReturnAnObject()
    {
        $this->subject->getTitles();
    }
}
