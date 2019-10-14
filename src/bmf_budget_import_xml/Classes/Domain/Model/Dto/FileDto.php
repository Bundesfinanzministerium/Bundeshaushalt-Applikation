<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class FileDto
{
    /**
     * Filename
     * e.g.: 'Haushalt_2015.v01.kapitel.corrected.xlsx'
     *
     * @var string
     * @validate NotEmpty
     */
    protected $name;

    /**
     * Type
     * e.g.: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
     *
     * @var string
     */
    protected $type;

    /**
     * Filename
     * e.g.: '/tmp/phpnoshcU'
     *
     * @var string
     */
    protected $tmp_name = '';

    /**
     * Error
     * e.g.: 0
     *
     * @var int
     */
    protected $error = 0;

    /**
     * Filesize
     * e.g.: 359710
     *
     * @var int
     */
    protected $size = 0;

    /**
     * Filename
     * e.g.: '/var/www/....'
     *
     * @var string
     */
    protected $absoluteFilename = '';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name = '')
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type = '')
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

// phpcs:disable
    /**
     * @param string $tmp_name
     */
    public function setTmp_name($tmp_name = '')
    {
        $this->tmp_name = $tmp_name;
    }
// phpcs:enable

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param int $error
     */
    public function setError($error = 0)
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size = 0)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getAbsoluteFilename()
    {
        return $this->absoluteFilename;
    }

    /**
     * @param string $absoluteFilename
     */
    public function setAbsoluteFilename($absoluteFilename = '')
    {
        $this->absoluteFilename = $absoluteFilename;
    }
}
