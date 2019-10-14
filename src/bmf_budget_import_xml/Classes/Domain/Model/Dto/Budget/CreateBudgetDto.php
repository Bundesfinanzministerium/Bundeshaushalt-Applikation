<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class CreateBudgetDto
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
     * Year property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $year = '';

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
     * @param string $title The title property to be set
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets the year
     *
     * @param string $year The year property to be set
     *
     * @return void
     */
    public function setYear($year)
    {
        $this->year = $year;
    }
}
