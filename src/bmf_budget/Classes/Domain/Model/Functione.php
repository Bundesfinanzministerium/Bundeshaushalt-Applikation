<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Functione extends AbstractStructure
{

    /**
     * Parent function element
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Functione
     */
    protected $functione;

    /**
     * Function child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione>
     * @cascade remove
     * @lazy
     */
    protected $functions;

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
        $this->functions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->titles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the parent function element
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Functione
     */
    public function getFunctione()
    {
        return $this->functione;
    }

    /**
     * Sets the parent function element
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $functione Function to be set
     *
     * @return void
     */
    public function setFunctione(\PPKOELN\BmfBudget\Domain\Model\Functione $functione)
    {
        $this->functione = $functione;
    }

    /**
     * Adds a child function
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $function The function to be added
     *
     * @return void
     */
    public function addFunction(\PPKOELN\BmfBudget\Domain\Model\Functione $function)
    {
        $this->functions->attach($function);
    }

    /**
     * Removes a child function
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $functionToRemove The function to be removed
     *
     * @return void
     */
    public function removeFunction(\PPKOELN\BmfBudget\Domain\Model\Functione $functionToRemove)
    {
        $this->functions->detach($functionToRemove);
    }

    /**
     * Returns all child functions
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione> $functions
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Sets all child functions
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione> $functions
     *        A recordset of Functions to be added
     *
     * @return void
     */
    public function setFunctions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $functions)
    {
        $this->functions = $functions;
    }
}
