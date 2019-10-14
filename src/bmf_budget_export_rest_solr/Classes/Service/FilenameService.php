<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class FilenameService
{
    private $year;
    private $account;
    private $flow;
    private $structure;
    private $address;
    private $type;

    private $jsonPath = '';
    private $htmlUrl = '';

    /**
     * @param array $options array of indexer options
     */
    public function __construct(
        $year = '',
        $account = '',
        $flow = '',
        $structure = '',
        $address = '',
        $type = ''
    ) {
        $this->year = $year;
        $this->account = $account;
        $this->flow = $flow;
        $this->structure = $structure;
        $this->address = $address;
        $this->type = $type;
        $this->process();
    }

    private function process()
    {
        /**
         * 1. segment: year
         *      "2012/xxxxx/xxxxx/xxxxx/xxxxx"
         */
        $directory = $this->year . '/';

        /**
         * 2. segment: account
         *      "2012/ist/xxxxx/xxxxx/xxxxx"
         */
        $directory .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
            'json.file.account.' . $this->account,
            'bmf_budget_export_rest_solr'
        ) . '/';

        /**
         * 3. segment: flow
         *      "2012/ist/einnahmen/xxxxx/xxxxx"
         */
        $directory .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
            'json.file.flow.' . $this->flow,
            'bmf_budget_export_rest_solr'
        ) . '/';

        /**
         * 4. segment: flow
         *      "2012/ist/einnahmen/einzelplan/xxxxx"
         */
        $directory .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
            'json.file.structure.' . $this->structure,
            'bmf_budget_export_rest_solr'
        ) . '/';

        /**
         * 5. segment: address
         *      "2012/ist/einnahmen/einzelplan/17"
         */
        $directory .= strtolower($this->type) !== 'root'
            ? $this->address . '/'
            : '';

        /**
         * build json bath
         */
        $this->jsonPath = 'rest/' . $directory . 'index.json';

        /**
         * build html url
         */
        $this->htmlUrl = rtrim($directory, '/') . '.html';
    }

    /**
     * Returning json path
     *
     * @return string
     */
    public function getJsonPath()
    {
        return $this->jsonPath;
    }

    /**
     * Returning html url
     *
     * @return string
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }
}
