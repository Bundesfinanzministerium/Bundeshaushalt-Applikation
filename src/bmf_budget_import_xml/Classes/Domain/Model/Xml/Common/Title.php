<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Xml\Common;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

abstract class Title
{
    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var Section
     */
    protected $section;

    /**
     * @var Chapter
     */
    protected $chapter;

    /**
     * @var Budgetgroup
     */
    protected $budgetgroup;

    /**
     * @var Titlegroup
     */
    protected $titlegroup;

    /**
     * @var string
     */
    protected $flow = '';

    /**
     * @var string
     */
    protected $funktion = '';

    /**
     * @var string
     */
    protected $group = '';

    /**
     * @var bool
     */
    protected $flexible = false;

    /**
     * @var string
     */
    protected $nr = '';

    /**
     * @var int
     */
    protected $page = 0;

    /**
     * @var int
     */
    protected $pageLink = 0;

    /**
     * @var float
     */
    protected $target = 0;

    /**
     * Actual constructor.
     *
     * @param \DOMElement $title
     */
    public function __construct(\DOMElement $title = null)
    {
        $this->initialize($title);
    }

    /**
     * Initialize Model
     *
     * @param \DOMElement $title
     */
    abstract protected function initialize(\DOMElement $title = null);

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return Chapter
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * @return Budgetgroup
     */
    public function getBudgetgroup()
    {
        return $this->budgetgroup;
    }

    /**
     * @return Titlegroup
     */
    public function getTitlegroup()
    {
        return $this->titlegroup;
    }

    /**
     * @return string
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * @return string
     */
    public function getFunktion()
    {
        return $this->funktion;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return boolean
     */
    public function isFlexible()
    {
        return $this->flexible;
    }

    /**
     * @return boolean
     */
    public function getFlexible()
    {
        return (bool)$this->flexible;
    }

    /**
     * @return string
     */
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageLink()
    {
        return $this->pageLink;
    }

    /**
     * @return float
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return float
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->chapter->getAddress() . $this->nr;
    }
}
