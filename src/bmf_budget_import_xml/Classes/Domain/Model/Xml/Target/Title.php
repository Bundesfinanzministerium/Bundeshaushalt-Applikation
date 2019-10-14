<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Target;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;
use PPK\BmfBudgetImportXml\Exception\BudgetImportException;
use PPK\BmfBudgetImportXml\Utility\LabelSanitizerUtility;

class Title extends Common\Title
{
    /**
     * @var float
     */
    protected $actual = 0;

    /**
     * Initialize Model
     *
     * @param \DOMElement $title
     * @throws BudgetImportException
     */
    protected function initialize(\DOMElement $title = null)
    {
        $parent = $title->parentNode;
        $flowNode = null;
        $sectionNode = null;
        $chapterNode = null;
        $budgetGroupNode = null;
        $titleGroupNode = null;

        if ($parent->nodeName === 'einnahmen-ausgaben-art') {
            $budgetGroupNode = $parent;
            $flowNode = $budgetGroupNode->parentNode;
        } elseif ($parent->nodeName === 'titelgruppe') {
            $titleGroupNode = $parent;
            $flowNode = $titleGroupNode->parentNode;
        } elseif ($parent->nodeName === 'einnahmen' || $parent->nodeName === 'ausgaben') {
            $flowNode = $parent;
        } else {
            throw new BudgetImportException('Invalid title node encountered in XML', 1484055397834);
        }

        $chapterNode = $flowNode->parentNode;
        $sectionNode = $chapterNode->parentNode;

        $this->section = $sectionNode !== null ? new Section($sectionNode) : null;
        $this->chapter = $chapterNode !== null ? new Chapter($chapterNode) : null;
        $this->budgetgroup = $budgetGroupNode !== null ? new Budgetgroup($budgetGroupNode) : null;
        $this->titlegroup = $titleGroupNode !== null ? new Titlegroup($titleGroupNode) : null;


        $this->label = LabelSanitizerUtility::sanitize($title->getElementsByTagName('text')->item(0)->nodeValue);

        if ($flowNode->localName === 'ausgaben') {
            $this->flow = 'expenses';
        } elseif ($flowNode->localName === 'einnahmen') {
            $this->flow = 'income';
        }

        $this->funktion = $title->getAttribute('fkt');
        $this->group = substr($title->getAttribute('nr'), 0, 3);
        $this->flexible = $title->getAttribute('flexibilisiert') === 'ja';
        $this->nr = $title->getAttribute('nr');
        $this->page = (int)$title->getAttribute('seite');
        $this->pageLink = (int) $this->page
            - (int) $title->getAttribute('einzelplan_seite_offset')
            + 1;

        $this->target = (int) $title->getElementsByTagName('soll')->item(0)->getAttribute('wert');
    }

    /**
     * @return float
     */
    public function getActual()
    {
        return $this->actual;
    }
}
