<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Titlegroup extends AbstractStructure
{

    /**
     * Parent section element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Section
     */
    protected $section;

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
}
