<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class Label extends AbstractEntity
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
     * @param string $filename absolute filepath
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
     * @return array
     */
    public function getFunktionen()
    {
        $result = [];
        $xpathQuery = '/haushalt/funktionen/funktion';

        /** @var \DOMElement $sections */
        $funktionen = $this->xpath->query($xpathQuery);

        /** @var \DOMElement $funktion */
        foreach ($funktionen as $funktion) {
            $result['idx-' . trim($funktion->getAttribute('nr'))] = [
                'nr' => trim($funktion->getAttribute('nr')),
                'label' => LabelSanitizerUtility::sanitize($funktion->getElementsByTagName('text')->item(0)->nodeValue)
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getGruppen()
    {
        $result = [];
        $xpathQuery = '/haushalt/gruppen/gruppe';

        /** @var \DOMElement $sections */
        $gruppen = $this->xpath->query($xpathQuery);

        /** @var \DOMElement $gruppe */
        foreach ($gruppen as $gruppe) {
            $result['idx-' . trim($gruppe->getAttribute('nr'))] = [
                'nr' => trim($gruppe->getAttribute('nr')),
                'label' => LabelSanitizerUtility::sanitize($gruppe->getElementsByTagName('text')->item(0)->nodeValue)
            ];
        }
        return $result;
    }
}
