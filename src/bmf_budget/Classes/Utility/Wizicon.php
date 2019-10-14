<?php
namespace PPKOELN\BmfBudget\Utility;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Localization\LocalizationFactory;

class Wizicon
{
    /**
     * Corresponding extensionkey like: "bmf_hhvis"
     *
     * @var string
     */
    protected $extensionKey = 'bmf_budget';

    /**
     * Corresponding languagefile like: "locallang.xlf"
     *
     * @var string
     */
    protected $languageFilename = 'locallang.xlf';

    /**
     * Adds the corresponding wizard icon
     *
     * @param array $wizardItems Array of all added Wizicons
     * @return array
     */
    public function proc(array $wizardItems = [])
    {
        $langfile = $this->getLanguageFile();

        $wizardItems['plugins_tx_bmfbudget_pi1'] = [
            'icon' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                'EXT:' . $this->extensionKey . '/Resources/Public/Icons/wizicon_pi1.png'
            ),
            'title' => $GLOBALS['LANG']->getLLL('pi1_wizicon.title', $langfile),
            'description' => $GLOBALS['LANG']->getLLL('pi1_wizicon.description', $langfile),
            'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=bmfbudget_pi1'
        ];

        return $wizardItems;
    }

    /**
     * Returns the corresponding languag file as array
     *
     * @return array|bool
     */
    public function getLanguageFile()
    {

        /* @var $parserFactory \TYPO3\CMS\Core\Localization\LocalizationFactory */
        $parserFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            LocalizationFactory::class
        );

        return $parserFactory->getParsedData(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
                $this->extensionKey,
                'Resources/Private/Language/' . $this->languageFilename
            ),
            $GLOBALS['LANG']->lang,
            'utf-8',
            1
        );
    }
}
