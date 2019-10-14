<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Title extends \PPK\BmfBudgetImportXml\Domain\Model\Xml\Target\Title
{
    /**
     * Page number of the supplementary document
     *
     * @var int
     */
    protected $pageSupplementary = 0;

    /**
     * Current value
     *
     * @var int
     */
    protected $current = 0;

    /**
     * Initialize Model
     *
     * @param \DOMElement $title
     * @throws \PPK\BmfBudgetImportXml\Exception\BudgetImportException
     */
    protected function initialize(\DOMElement $title = null)
    {
        parent::initialize($title);

        // reading page-supplementary from xml
        $this->pageSupplementary = $title->getAttribute(
            'seitenachtrag'
        );
        // reading current value from xml
        $this->current = (int) $title->getElementsByTagName('bisher')->item(0)->getAttribute('wert');
    }

    /**
     * Returns the page number of the supplementary document
     *
     * @return int
     */
    public function getPageSupplementary()
    {
        return $this->pageSupplementary;
    }

    /**
     * Returns the current value
     *
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }
}
