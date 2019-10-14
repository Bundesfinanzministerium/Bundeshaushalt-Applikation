<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractStructure extends AbstractValue
{

    /**
     * Parent budget element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    protected $budget;

    /**
     * Address property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $address = '';

    /**
     * Title property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Title child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Title>
     * @lazy
     * @cascade remove
     */
    protected $titles;


    /**
     * Returns the parent section element
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Section
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets the parent section element
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section Section to be set
     *
     * @return void
     */
    public function setBudget(\PPKOELN\BmfBudget\Domain\Model\Budget $budget)
    {
        $this->budget = $budget;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address The address property to be added
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
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
     * Adds a title
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
     * Removes a title
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
     * Returns titles
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Title> $titles
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * Sets titles
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Title> $titles A recordset
     *        of titles to be added
     * @return void
     */
    public function setTitles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles)
    {
        $this->titles = $titles;
    }
}
