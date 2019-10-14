<?php
namespace PPK\BmfBudgetImportXml\Domain\Model;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Page extends AbstractEntity
{
    /**
     * Title property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * DokType property
     *
     * @var int
     */
    protected $doktype = 0;

    /**
     * TS Config property
     *
     * @var string
     */
    protected $tsConfig = '';

    /**
     * Sorting property
     *
     * @var int
     */
    protected $sorting = 0;

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
     * @return int
     */
    public function getDoktype()
    {
        return $this->doktype;
    }

    /**
     * @param int $doktype
     */
    public function setDoktype($doktype)
    {
        $this->doktype = $doktype;
    }

    /**
     * @return string
     */
    public function getTsConfig()
    {
        return $this->tsConfig;
    }

    /**
     * @param string $tsConfig
     */
    public function setTsConfig($tsConfig)
    {
        $this->tsConfig = $tsConfig;
    }

    /**
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }
}
