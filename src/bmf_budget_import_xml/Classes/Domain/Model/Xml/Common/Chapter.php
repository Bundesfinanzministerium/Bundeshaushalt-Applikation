<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;

abstract class Chapter
{
    /**
     * Section label (e.g.: 'Verwaltungseinnahmen')
     *
     * @var string
     */
    protected $label = '';

    /**
     * Chapter address (e.g.: '0405')
     *
     * @var string
     */
    protected $address = '';

    /**
     * Chapter constructor.
     *
     * @param \DOMElement $chapter
     */
    public function __construct(\DOMElement $chapter)
    {
        $this->initialize($chapter);
    }

    /**
     * Initialize model
     *
     * @param \DOMElement $chapter
     */
    protected function initialize(\DOMElement $chapter)
    {
        $this->label = LabelSanitizerUtility::sanitize($chapter->getElementsByTagName('text')->item(0)->nodeValue);
        $this->address = $chapter->getAttribute('nr');
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
     * Returns the chapter address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
