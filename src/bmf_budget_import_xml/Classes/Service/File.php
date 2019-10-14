<?php
namespace PPK\BmfBudgetImportXml\Service;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto;

class File implements SingletonInterface
{
    /**
     * Specify the typo3temp directory (e.g. tx_bmf_budget...)
     *
     * @var string
     */
    protected $t3directory = 'tx_bmf_budget_import_xml/';

    /**
     * Internal error array
     *
     * @var array
     */
    public $error = [];

    /**
     * Global constructor
     */
    public function __construct()
    {
        $this->error = ['status' => false, 'events' => []];
    }

    /**
     * Prepare uploaded file for use
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto $file Corresponding file
     * @return \PPK\BmfBudgetImportXml\Domain\Model\Dto\FileDto
     */
    public function prepareUploadedFile(FileDto $file)
    {
        if ($file->getError()) {
            $this->error['status'] = true;
            $this->error['events'][] = [
                'code' => '65465464654.' . $file->getError(),
                'message' => LocalizationUtility::translate(
                    'error.form.upload.file.' . $file->getError(),
                    'bmf_budget_import_xml'
                )
            ];
            return false;
        }

        // prepare new filename
        $filename = md5(time());

        $dir = GeneralUtility::getFileAbsFileName('typo3temp/' . $this->t3directory);
        if (!file_exists($dir) && !is_dir($dir)) {
            GeneralUtility::mkdir_deep($dir);
            if (!file_exists($dir) && !is_dir($dir)) {
                throw new \RuntimeException('Unable to create directory "' . $dir . '"! Permission issues.');
            }
        }

        // prepare new file
        $file->setAbsoluteFilename(
            GeneralUtility::getFileAbsFileName($dir . $filename)
        );

        // move file to temporary folder
        GeneralUtility::upload_copy_move(
            $file->getTmpName(),
            $file->getAbsoluteFilename()
        );

        // everything is fine ... return processed informations
        return $file;
    }

    /**
     * Check different locations for file source and returns the right one
     *
     * @param string $filename Filename incl. path
     * @return bool|string Return absolute path if everything went fine else FALSE
     */
    public function getAbsoluteFilename($filename = null)
    {
        // check if valid file is present
        if (is_file(GeneralUtility::getFileAbsFileName('typo3temp/' . $this->t3directory . $filename))) {
            return GeneralUtility::getFileAbsFileName(
                'typo3temp/' . $this->t3directory . $filename
            );
        } elseif (is_file(GeneralUtility::getFileAbsFileName($filename))) {
            return GeneralUtility::getFileAbsFileName(
                $filename
            );
        } else {
            $this->error['status'] = true;
            $this->error['events'][] = ['code' => '1406289914', 'label' => 'file not found'];
            return false;
        }
    }
}
