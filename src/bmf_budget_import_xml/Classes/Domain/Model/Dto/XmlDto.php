<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class XmlDto extends UploadDto
{
    /**
     * Section
     *
     * @var string
     * @validate NotEmpty
     */
    protected $section = '';

    /**
     * Return the sheet index
     *
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Sets the section
     *
     * @param string $section
     *
     * @return void
     */
    public function setSection($section = '')
    {
        $this->section = $section;
    }
}
