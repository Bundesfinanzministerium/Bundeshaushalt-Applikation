<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Budgetgroup extends AbstractValue
{

    /**
     * Parent section element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Section
     */
    protected $section;

    /**
     * Title property
     *
     * @var string
     */
    protected $title = '';

    /**
     * Title child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Title>
     * @cascade remove
     */
    protected $titles;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->titles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the parent section element
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Sets the parent section element
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section Section to be set
     *
     * @return void
     */
    public function setSection(\PPKOELN\BmfBudget\Domain\Model\Section $section)
    {
        $this->section = $section;
    }

    /**
     * Returns the title property
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title property
     *
     * @param string $title Title property
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Adds a child title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Title $title The title to be added
     *
     * @return void
     */
    public function addTitle(\PPKOELN\BmfBudget\Domain\Model\Title $title)
    {
        $this->titles->attach($title);
    }

    /**
     * Removes a child title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Title $titleToRemove The title to be removed
     *
     * @return void
     */
    public function removeTitle(\PPKOELN\BmfBudget\Domain\Model\Title $titleToRemove)
    {
        $this->titles->detach($titleToRemove);
    }

    /**
     * Returns all child titles
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Title> $titles
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * Sets all child titles
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles A recordset of titles to be added
     *
     * @return void
     */
    public function setTitles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles)
    {
        $this->titles = $titles;
    }
}
