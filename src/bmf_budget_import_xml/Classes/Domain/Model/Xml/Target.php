<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class Target extends AbstractEntity
{
    /**
     * Absolute filename
     *
     * @var string
     */
    protected $file;

    /**
     * DOM Object
     *
     * @var \DOMDocument
     */
    protected $xml;

    /**
     * XPath Object
     *
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @var array
     */
    protected $error;

    /**
     * Actual constructor.
     *
     * @param FileDto $file
     */
    public function __construct(FileDto $file = null)
    {
        $this->initialize($file->getAbsoluteFilename());
    }

    /**
     * Initialize Model
     *
     * @param $filename string absolute filepath
     */
    protected function initialize($filename)
    {
        try {
            // create dom object
            $this->xml = new \DOMDocument();
            $this->xml->load($filename);
            // create xpath
            $this->xpath = new \DomXpath($this->xml);
        } catch (\Exception $exceptionObj) {
            $this->error['status'] = true;
            $this->error['events'][] = ['code' => '1334308991', 'label' => 'problems parsing file'];
        }
    }

    /**
     * Returning all section nr's as array
     *
     * @return array
     */
    public function getSections()
    {
        $sectionArray = [];

        /** @var \DOMNodeList $sections */
        $sections = $this->xpath->query('/haushalt/einzelplan');

        /** @var \DOMElement $section */
        foreach ($sections as $section) {
            $sectionArray[] = $section->getAttribute('nr');
        }
        return $sectionArray;
    }

    /**
     * @param string $budgetYear
     * @param string $sectionNumber
     * @param string $chapterNumber
     * @return \DOMNodeList
     */
    public function getTitles($budgetYear = '', $sectionNumber = '', $chapterNumber = '')
    {
        $xpathQuery = '//titel';
        if (trim($sectionNumber) !== '') {
            $xpathQuery = '/haushalt/einzelplan[@nr = \'' . $sectionNumber . '\']//titel';
        }

        /** @var \DOMElement $sections */
        $titles = $this->xpath->query($xpathQuery);
        return $titles;
    }
}
