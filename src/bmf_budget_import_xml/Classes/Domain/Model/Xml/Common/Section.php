<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;

abstract class Section
{
    /**
     * Section label (e.g.: 'Verwaltungseinnahmen')
     *
     * @var string
     */
    protected $label = '';

    /**
     * Section address (e.g.: '0405')
     *
     * @var string
     */
    protected $address = '';

    /**
     * Section constructor.
     *
     * @param \DOMElement $section
     */
    public function __construct(\DOMElement $section)
    {
        $this->initialize($section);
    }

    /**
     * Initialize model
     *
     * @param \DOMElement $section
     */
    protected function initialize(\DOMElement $section)
    {
        $this->label = LabelSanitizerUtility::sanitize($section->getElementsByTagName('text')->item(0)->nodeValue);
        $this->address = $section->getAttribute('nr');
    }

    /**
     * Returns the section label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the section address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
