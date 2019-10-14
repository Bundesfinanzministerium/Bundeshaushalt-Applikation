<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Groupe extends AbstractStructure
{
    /**
     * Parent group element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Groupe
     */
    protected $groupe;

    /**
     * Group child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe>
     * @cascade remove
     * @lazy
     */
    protected $groups;

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
        $this->groups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->titles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the parent group element
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Sets the parent group element
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $groupe Group to be set
     *
     * @return void
     */
    public function setGroupe(\PPKOELN\BmfBudget\Domain\Model\Groupe $groupe)
    {
        $this->groupe = $groupe;
    }

    /**
     * Adds a child group
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $group The group to be added
     *
     * @return void
     */
    public function addGroup(\PPKOELN\BmfBudget\Domain\Model\Groupe $group)
    {
        $this->groups->attach($group);
    }

    /**
     * Removes a child group
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $groupToRemove The group to be removed
     *
     * @return void
     */
    public function removeGroup(\PPKOELN\BmfBudget\Domain\Model\Groupe $groupToRemove)
    {
        $this->groups->detach($groupToRemove);
    }

    /**
     * Returns all child groups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe> $groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Sets all child groups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe> $groups
     *        A recordset of groups to be added
     * @return void
     */
    public function setGroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $groups)
    {
        $this->groups = $groups;
    }
}
