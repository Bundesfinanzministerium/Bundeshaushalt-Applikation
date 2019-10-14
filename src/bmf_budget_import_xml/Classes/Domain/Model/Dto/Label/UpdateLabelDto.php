<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto\Label;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto;

class UpdateLabelDto extends UploadDto
{
    /**
     * current step
     *
     * @var int
     */
    protected $step = 0;

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
}
