<?php
namespace PPK\BmfBudgetImportXml\Domain\Model\Dto;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class UploadDto extends BudgetDto
{
    /**
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto
     * @validate NotEmpty
     */
    protected $file;

    /**
     * Session
     *
     * @var string
     */
    protected $session = '';

    /**
     * @return \PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto $file
     *
     * @return void
     */
    public function setFile(FileDto $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}
