<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Writer;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ApacheSolrForTypo3\Solr;
use PPKOELN\BmfBudget\Domain\Model\Budget;

class Json
{
    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\MetaParser
     * @inject
     */
    protected $metaParser;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\DetailParser
     * @inject
     */
    protected $detailParser;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\ChildParser
     * @inject
     */
    protected $childParser;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\ParentParser
     * @inject
     */
    protected $parentParser;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\RelatedParser
     * @inject
     */
    protected $relatedParser;

    /**
     *
     * @param Budget $budget
     * @param string $year
     * @param string $account
     * @param string $flow
     * @param string $structure
     * @param string $address
     * @param array $data
     * @param \PPKOELN\BmfBudgetExportRestSolr\Service\FilenameService $filenameService
     * @return object
     */
    public function get(
        Budget $budget = null,
        $year = '',
        $account = '',
        $flow = '',
        $structure = '',
        $address = '',
        array $data = [],
        $filenameService = null
    ) {

        $result = [];

        // prepare parent parameter
        $result['meta'] = $this->metaParser->get(
            $structure,
            $year,
            $account,
            $flow,
            $address
        );

        // prepare detail parameter
        $result['detail'] = $this->detailParser->get(
            $data['detail'],
            $structure,
            $account,
            $flow
        );

        if (strtolower($data['detail']['type']) === 'root') {
            $result['childs'] = $this->childParser->get(
                $account,
                $flow,
                $data['list'],
                ''
            );
        }

        if (strtolower($data['detail']['type']) === 'structure') {
            $result['childs'] = $this->childParser->get(
                $account,
                $flow,
                $data['list'],
                $data['detail']['entity']->getAddress()
            );
            $result['parents'] = $this->parentParser->get(
                $structure,
                $account,
                $flow,
                $budget,
                $data['detail']['entity'],
                $data['rootpath']
            );
        }

        if (strtolower($data['detail']['type']) === 'title') {
            $result['parents'] = $this->parentParser->get(
                $structure,
                $account,
                $flow,
                $budget,
                $data['detail']['entity'],
                $data['rootpath']
            );
            $result['related'] = $this->relatedParser->get($data['detail']['entity']);
        }

        /**
         * Prepare solr document
         */
        $apacheSolrDocument = GeneralUtility::makeInstance(
            \Apache_Solr_Document::class
        );

        $apacheSolrDocument->setField(
            'id',
            Solr\Util::getDocumentId(
                'tx_bmfbudgetexportrestsolr_export-' . $structure,
                2,
                $filenameService->getJsonPath()
            )
        );

        $apacheSolrDocument->setField(
            'type',
            'tx_bmfbudgetexportrestsolr_export-rest'
        );

        $apacheSolrDocument->setField(
            'appKey',
            'EXT:solr'
        );

        $apacheSolrDocument->setField(
            'filePublicUrl',
            $filenameService->getJsonPath()
        );

        $apacheSolrDocument->setField(
            'budgetData',
            json_encode(
                $result,
                JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE
            )
        );

        return $apacheSolrDocument;
    }
}
