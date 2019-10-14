<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Service\AbstractService;
use \TYPO3\CMS\Core\SingletonInterface;
use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\Util;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TimelineWriteService extends AbstractService implements SingletonInterface
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * @var \PPKOELN\BmfBudget\Service\Structure\SectionService
     * @inject
     */
    protected $serviceStructureSection;

    /**
     * @var \PPKOELN\BmfBudget\Service\Structure\FunctioneService
     * @inject
     */
    protected $serviceStructureFunction;

    /**
     * @var \PPKOELN\BmfBudget\Service\Structure\GroupeService
     * @inject
     */
    protected $serviceStructureGroup;

    /**
     * @var \PPKOELN\BmfBudget\Service\Page\TitleService
     * @inject
     */
    protected $servicePageTitle;

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
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * Parent parser
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\ParentParser
     * @inject
     */
    protected $parentParser;

    /**
     * Related parser
     *
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Parser\RelatedParser
     * @inject
     */
    protected $relatedParser;

    /**
     * Constructor
     *
     * @param array $options array of indexer options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget|NULL $budget
     * @param string $structure
     * @param string $address
     * @param array $data
     * @return bool
     * @throws \ApacheSolrForTypo3\Solr\NoSolrConnectionFoundException
     * @throws \Apache_Solr_HttpTransportException
     */
    public function json(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget = null,
        $structure = '',
        $address = '',
        array $data = []
    ) {
        $result = $data;

        // Prepare json file equivalent to store in index
        $directory = '';

        if (strlen($address) === 9) {
            $directory .= $address;
        } else {
            if ($address === 'root') {
                $directory .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'json.file.structure.' . $structure,
                    'bmf_budget_export_rest_solr'
                );
            } else {
                $directory .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'json.file.structure.' . $structure,
                    'bmf_budget_export_rest_solr'
                ) . '/';
                $directory .= $address;
            }
        }

        $filename = 'rest-timeline/' . $directory . '.html';

        $documentForRest = GeneralUtility::makeInstance('Apache_Solr_Document');
        $documentForRest->setField('id', Util::getDocumentId(
            'tx_bmfbudgetexportrestsolr_export-timeline',
            2,
            $filename
        ));

        /*
         * possible values for tx_bmfbudgetexportrestsolr_export are:
         *      tx_bmfbudgetexportrestsolr_export-rest
         *      tx_bmfbudgetexportrestsolr_export-rest-timeline
         */

        $documentForRest->setField('type', 'tx_bmfbudgetexportrestsolr_export-rest-timeline');
        $documentForRest->setField('appKey', 'EXT:solr');

        $documentForRest->setField('filePublicUrl', $filename);

        $documentForRest->setField('budgetData', json_encode($result, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));

        /** @var ConnectionManager $connectionManager */
        $connectionManager = GeneralUtility::makeInstance(ConnectionManager::class);

        $solr = $connectionManager->getConnectionByPageId(2);
        $response = $solr->getWriteService()->addDocuments([$documentForRest]);

        $itemIndexed = false;
        if ($response->getHttpStatus() === 200) {
            $itemIndexed = true;
        }
        return $itemIndexed;
    }
}
