<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;

abstract class Titlegroup
{
    /**
     * Titlegroup label (e.g.: 'Versorgung der Beamtinnen und Beamten sowie der Richterinnen und Richter')
     *
     * @var string
     */
    protected $label = '';

    /**
     * Titlegroup address (e.g.: '57')
     *
     * @var string
     */
    protected $address = '';

    /**
     * Titlegroup constructor.
     *
     * @param \DOMElement $titlegroup
     */
    public function __construct(\DOMElement $titlegroup)
    {
        $this->initialize($titlegroup);
    }

    /**
     * Initialize model
     *
     * @param \DOMElement $titlegroup
     */
    protected function initialize(\DOMElement $titlegroup)
    {
        $this->label = LabelSanitizerUtility::sanitize($titlegroup->getElementsByTagName('text')->item(0)->nodeValue);
        $this->address = $titlegroup->getAttribute('nr');
    }

    /**
     * Returns the titlegroup label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the titlegroup address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
