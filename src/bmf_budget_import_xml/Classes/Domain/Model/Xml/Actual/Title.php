<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;
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
     */
    protected function initialize(\DOMElement $title = null)
    {
        $parent = $title->parentNode;
        $flowNode = null;
        $sectionNode = null;
        $chapterNode = null;
        $budgetGroupNode = null;
        $titleGroupNode = null;

        if ($parent->nodeName === 'ausgaben') {
            $flowNode = $parent;
            $chapterNode = $flowNode->parentNode;
            $sectionNode = $chapterNode->parentNode;
        } elseif ($parent->nodeName === 'einnahmen') {
            $flowNode = $parent;
            $chapterNode = $flowNode->parentNode;
            $sectionNode = $chapterNode->parentNode;
        } elseif ($parent->nodeName === 'einnahmen-ausgaben-art') {
            $budgetGroupNode = $parent;
            $flowNode = $budgetGroupNode->parentNode;
            $chapterNode = $flowNode->parentNode;
            $sectionNode = $chapterNode->parentNode;
        } elseif ($parent->nodeName === 'titelgruppe') {
            $titleGroupNode = $parent;
            $flowNode = $titleGroupNode->parentNode;
            $chapterNode = $flowNode->parentNode;
            $sectionNode = $chapterNode->parentNode;
        }

        $this->section = $sectionNode !== null ? new Section($sectionNode) : null;
        $this->chapter = $chapterNode !== null ? new Chapter($chapterNode) : null;
        $this->budgetgroup = $budgetGroupNode !== null ? new Budgetgroup($budgetGroupNode) : null;
        $this->titlegroup = $titleGroupNode !== null ? new Titlegroup($titleGroupNode) : null;


        $this->label = LabelSanitizerUtility::sanitize($title->getElementsByTagName('text')->item(0)->nodeValue);

        if ($title->getAttribute('ein-aus-art') === 'flex-ausgaben') {
            $this->flow = 'expenses';
        } elseif ($title->getAttribute('ein-aus-art') === 'ausgaben') {
            $this->flow = 'expenses';
        } elseif ($title->getAttribute('ein-aus-art') === 'einnahmen') {
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

        $this->target = $title->getElementsByTagName('soll')->item(0)->getAttribute('wert');
        $this->actual = $title->getElementsByTagName('ist')->item(0)->getAttribute('wert');

        $this->target = ((float) str_replace(',', '.', str_replace('.', '', $this->target)) / 1000)
            * (strpos($this->target, '-') ? -1 : 1 );
        $this->actual = (float) str_replace(',', '.', str_replace('.', '', $this->actual))
            * (strpos($this->actual, '-') ? -1 : 1 );
    }

    /**
     * @return float
     */
    public function getActual()
    {
        return $this->actual;
    }
}
