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

class WriteService extends AbstractService implements SingletonInterface
{
    /**
     * @var \ApacheSolrForTypo3\Solr\ConnectionManager
     * @inject
     */
    protected $solrConnectionManager;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Writer\Json
     * @inject
     */
    protected $jsonWriterService;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Writer\Html
     * @inject
     */
    protected $htmlWriterService;

    /**
     * @var \PPKOELN\BmfBudgetExportRestSolr\Service\Writer\Text
     * @inject
     */
    protected $textWriterService;

    /**
     * Write datas to solr
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget|null $budget
     * @param string $year
     * @param string $account
     * @param string $flow
     * @param string $structure
     * @param string $address
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function solr(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget = null,
        $year = '',
        $account = '',
        $flow = '',
        $structure = '',
        $address = '',
        array $data = []
    ) {
        $filenameService = new \PPKOELN\BmfBudgetExportRestSolr\Service\FilenameService(
            $year,
            $account,
            $flow,
            $structure,
            $address,
            $data['detail']['type']
        );

        $solrDocument[] = $this->jsonWriterService
            ->get($budget, $year, $account, $flow, $structure, $address, $data, $filenameService);

        /*
        $solrDocument[] = $this->htmlWriterService
            ->get($budget, $year, $account, $flow, $structure, $address, $data, $filenameService);
        */

        $solrDocument[] = $this->textWriterService
            ->get($budget, $year, $account, $flow, $structure, $address, $data, $filenameService);

        /** @var \ApacheSolrForTypo3\Solr\System\Solr\SolrConnection $solrConnection */
        $solrConnection = $this->solrConnectionManager->getConnectionByPageId(2);

        /** @var \Apache_Solr_Response $response */
        $response = $solrConnection->getWriteService()->addDocuments($solrDocument);

        return $response->getHttpStatus() == 200;
    }
}
