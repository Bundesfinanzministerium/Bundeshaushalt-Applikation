<?php
namespace PPK\BmfBudgetImportXml\Service;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\SingletonInterface;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class Xml implements SingletonInterface
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
    public $error;

    /**
     * Initialize action
     *
     * @param FileDto $file
     * @return null
     */
    public function initialize(FileDto $file)
    {
        $this->file = $file->getAbsoluteFilename();
        $this->error = ['status' => false, 'events' => []];

        return $this->loadDocument();
    }

    /**
     * Check if file is valid
     *
     * @return bool
     */
    public function loadDocument()
    {
        try {
            // create dom object
            $this->xml = new \DOMDocument();
            $this->xml->load($this->file);

            // create xpath
            $this->xpath = new \DomXpath($this->xml);
        } catch (\Exception $exceptionObj) {
            $this->error['status'] = true;
            $this->error['events'][] = ['code' => '1334308991', 'label' => 'problems parsing file'];
        }
        return $this->error['status'] ? false : true;
    }

    /**
     * Get all sections
     *
     * @return mixed
     */
    public function getSections()
    {
        /** @var \DOMNodeList $p2 */
        $p2 = $this->xpath->query('//einzelplan[@nr = \'01\']');
        /** @var \DOMElement $p3 */
        $p3 = $p2->item(0);

        $p3->ownerDocument->formatOutput = true;
        $this->xml->saveXML($p3);

        $sectionArray = [];

        /** @var \DOMNodeList $sections */
        $sections = $this->xml->getElementsByTagName("einzelplan");

        /** @var \DOMElement $section */
        foreach ($sections as $section) {
            $sectionArray[] = $section->getAttribute('nr');
        }

        return $sectionArray;
    }

    public function getSection($section = '')
    {
    }
}
