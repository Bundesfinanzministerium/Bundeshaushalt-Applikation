<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary;

use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;

class CreateSupplementaryDto extends BudgetDto
{
    /**
     * RootSysFolderPid
     *
     * @var int
     */
    protected $rootSysFolderPid = '';

    /**
     * Title property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * current step
     *
     * @var int
     */
    protected $step = 0;

    /**
     * Returns the root sysfolder pid
     *
     * @return int
     */
    public function getRootSysFolderPid()
    {
        return $this->rootSysFolderPid;
    }

    /**
     * Sets the root sysfolder pid
     *
     * @param int $rootSysFolderPid
     *
     * @return void
     */
    public function setRootSysFolderPid($rootSysFolderPid)
    {
        $this->rootSysFolderPid = $rootSysFolderPid;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

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
