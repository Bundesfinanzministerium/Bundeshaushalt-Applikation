<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;

abstract class Budgetgroup
{
    /**
     * Budgetgroup label
     *
     * @var string
     */
    protected $label = '';

    /**
     * Budgetgroup constructor.
     *
     * @param \DOMElement $budgetgroup
     */
    public function __construct(\DOMElement $budgetgroup)
    {
        $this->initialize($budgetgroup);
    }

    /**
     * Initialize model
     *
     * @param \DOMElement $budgetgroup
     */
    protected function initialize(\DOMElement $budgetgroup)
    {
        $this->label = LabelSanitizerUtility::sanitize($budgetgroup->getElementsByTagName('text')->item(0)->nodeValue);
    }

    /**
     * Returns the budgetgroup label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
