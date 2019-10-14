<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class SupplementaryBudget extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Sorting property
     *
     * @var int
     */
    protected $sorting = 0;

    /**
     * Title property
     *
     * @var string
     */
    protected $title = '';

    /**
     * Supplementary Title child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     * @cascade remove
     * @lazy
     */
    protected $supplementaryTitles;

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
        $this->supplementaryTitles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * @param string $title The title property to be added
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Adds a supplementary-title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryTitle
     *        The supplementary-title to be removed
     *
     * @return void
     */
    public function addSupplementaryTitle(\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryTitle)
    {
        $this->supplementaryTitles->attach($supplementaryTitle);
    }

    /**
     * Removes a supplementary-title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryTitleToRemove
     *        The supplementary-title to be removed
     *
     * @return void
     */
    public function removeSupplementaryTitle(
        \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryTitleToRemove
    ) {
        $this->supplementaryTitles->detach($supplementaryTitleToRemove);
    }

    /**
     * Returns all child supplementary-title
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     *         $supplementaryTitles
     */
    public function getSupplementaryTitles()
    {
        return $this->supplementaryTitles;
    }

    /**
     * Sets all child supplementary-title
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     *        $supplementaryTitles A recordset of supplementary-titles to be added
     *
     * @return void
     */
    public function setSupplementaryTitles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $supplementaryTitles)
    {
        $this->supplementaryTitles = $supplementaryTitles;
    }

    /**
     * @param int $sorting
     */
    public function setSorting(
        $sorting
    ) {
        $this->sorting = $sorting;
    }

    /**
     * Returns the title property
     *
     * @return string $title
     */
    public function getSorting()
    {
        return $this->sorting;
    }
}
