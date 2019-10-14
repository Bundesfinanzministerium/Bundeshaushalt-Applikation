<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Section extends AbstractStructure
{

    /**
     * Parent section element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Section
     */
    protected $section;

    /**
     * Section child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section>
     * @cascade remove
     * @lazy
     */
    protected $sections;

    /**
     * Budgetgroup child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Budgetgroup>
     * @cascade remove
     * @lazy
     */
    protected $budgetgroups;

    /**
     * Titlegroup child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Titlegroup>
     * @cascade remove
     * @lazy
     */
    protected $titlegroups;

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
        $this->sections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->titles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->budgetgroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->titlegroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Adds a section child
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section The section to be added
     *
     * @return void
     */
    public function addSection(\PPKOELN\BmfBudget\Domain\Model\Section $section)
    {
        $this->sections->attach($section);
    }

    /**
     * Removes a section child
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $sectionToRemove The section to be removed
     *
     * @return void
     */
    public function removeSection(\PPKOELN\BmfBudget\Domain\Model\Section $sectionToRemove)
    {
        $this->sections->detach($sectionToRemove);
    }

    /**
     * Returns all sections childs
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section> $sections
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Sets all sections childs
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section> $sections
     *        A recordset of sections to be added
     *
     * @return void
     */
    public function setSections(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Adds a budgetgroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup The budgetgroup to be added
     *
     * @return void
     */
    public function addBudgetgroup(\PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup)
    {
        $this->budgetgroups->attach($budgetgroup);
    }

    /**
     * Removes a budgetgroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroupToRemove The budgetgroup to be removed
     *
     * @return void
     */
    public function removeBudgetgroup(\PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroupToRemove)
    {
        $this->budgetgroups->detach($budgetgroupToRemove);
    }

    /**
     * Returns all child budgetgroups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Budgetgroup> $budgetgroups
     */
    public function getBudgetgroups()
    {
        return $this->budgetgroups;
    }

    /**
     * Sets all child budgetgroups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Budgetgroup> $budgetgroups
     *        A recordset of budgetgroups to be added
     *
     * @return void
     */
    public function setBudgetgroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $budgetgroups)
    {
        $this->budgetgroups = $budgetgroups;
    }

    /**
     * Adds a titlegroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup The titlegroup to be added
     *
     * @return void
     */
    public function addTitlegroup(\PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup)
    {
        $this->titlegroups->attach($titlegroup);
    }

    /**
     * Removes a titlegroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroupToRemove The titlegroup to be removed
     *
     * @return void
     */
    public function removeTitlegroup(\PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroupToRemove)
    {
        $this->titlegroups->detach($titlegroupToRemove);
    }

    /**
     * Returns all child titlegroups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Titlegroup> $titlegroups
     */
    public function getTitlegroups()
    {
        return $this->titlegroups;
    }

    /**
     * Sets all child titlegroups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Titlegroup> $titlegroups
     *        A recordset of titlegroups to be added
     *
     * @return void
     */
    public function setTitlegroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $titlegroups)
    {
        $this->titlegroups = $titlegroups;
    }
}
