<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class SupplementaryTitle extends AbstractValue
{

    /**
     * Actual page property
     *
     * @var int
     */
    protected $actualPage = 0;

    /**
     * Target page property
     *
     * @var int
     */
    protected $targetPage = 0;

    /**
     * Parent supplementarybudget entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     */
    protected $supplementarybudget;

    /**
     * Returns the actual page
     *
     * @return int $actualPage
     */
    public function getActualPage()
    {
        return $this->actualPage;
    }

    /**
     * Sets the actual page
     *
     * @param int $actualPage Corresponding actual pdf page
     *
     * @return void
     */
    public function setActualPage($actualPage)
    {
        $this->actualPage = $actualPage;
    }

    /**
     * Returns the target page
     *
     * @return int $targetPage
     */
    public function getTargetPage()
    {
        return $this->targetPage;
    }

    /**
     * Sets the target page
     *
     * @param int $targetPage Corresponding target pdf page
     *
     * @return void
     */
    public function setTargetPage($targetPage)
    {
        $this->targetPage = $targetPage;
    }

    /**
     * Returns the parent supplementarybudget entity
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementarybudget
     */
    public function getSupplementarybudget()
    {
        return $this->supplementarybudget;
    }
}
