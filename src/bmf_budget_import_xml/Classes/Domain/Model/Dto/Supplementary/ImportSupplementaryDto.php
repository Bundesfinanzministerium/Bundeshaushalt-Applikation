<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto;

class ImportSupplementaryDto extends UploadDto
{
    /**
     * current step
     *
     * @var int
     */
    protected $step = 0;

    /**
     * SupplementaryBudget property
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     * @validate NotEmpty
     */
    protected $supplementaryBudget;

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * Return the Budget
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget
     */
    public function getSupplementaryBudget()
    {
        return $this->supplementaryBudget;
    }

    /**
     * Sets the Budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudget
     *
     * @return void
     */
    public function setSupplementaryBudget(
        \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudget = null
    ) {
        $this->supplementaryBudget = $supplementaryBudget;
    }
}
