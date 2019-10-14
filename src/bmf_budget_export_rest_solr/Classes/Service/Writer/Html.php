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

class Html
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
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var \ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository
     * @inject
     */
    protected $siteRepository;

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
     * @throws \Exception
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
        // index in language de (else: default language of crawler)
        $GLOBALS['BE_USER']->uc['lang'] = 'de';

        switch (strtolower($structure)) {
            case 'section':
                $result = $this->serviceStructureSection->get($budget, $account, $flow, $address);
                break;
            case 'function':
                $result = $this->serviceStructureFunction->get($budget, $account, $flow, $address);
                break;
            case 'group':
                $result = $this->serviceStructureGroup->get($budget, $account, $flow, $address);
                break;
            default:
                throw new \Exception('Unknown structure');
        }

        $referrer = [
            'year' => $year,
            'account' => $account,
            'flow' => $flow,
            'structure' => $structure,
            'address' => $address
        ];

        // get page title
        $pageTitle = $this->servicePageTitle->getPageTitle($result['detail'], $referrer);

        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );


        /**
         * Preparing preview rendering
         */
        $previewView = GeneralUtility::makeInstance(
            \TYPO3\CMS\Fluid\View\StandaloneView::class
        );

        $previewView->getRequest()->setControllerExtensionName('bmf_budget');

        $previewView->setFormat('html');

        $previewView->setLayoutRootPath(
            $this->getRootPath($extbaseFrameworkConfiguration, 'layoutRootPaths')
        );

        $previewView->setPartialRootPath(
            $this->getRootPath($extbaseFrameworkConfiguration, 'partialRootPaths')
        );

        $previewView->setTemplatePathAndFilename(
            $this->getRootPath($extbaseFrameworkConfiguration, 'templateRootPaths') . 'Budget/Show.html'
        );

        /**
         * Assign parameter to view
         */
        $previewView->assign('budgets', $this->budgetRepository->findAll());
        $previewView->assign('result', $result);
        $previewView->assign('referrer', $referrer);

        /**
         * Render preview
         */
        $teaser = $previewView->render();

        /**
         * Cleanup preview
         */
        $this->typoSearchTags($teaser);

        $teaser = strip_tags($pageTitle . ' ' . $teaser);

        /** @var \Apache_Solr_Document $documentForIndexSearch */
        $documentForIndexSearch = GeneralUtility::makeInstance('Apache_Solr_Document');

        // required fields
        $documentForIndexSearch->setField(
            'id',
            Solr\Util::getDocumentId(
                'tx_bmfbudgetexportrestsolr_searchresult-' . $structure,
                2,
                $filenameService->getJsonPath()
            )
        );
        $documentForIndexSearch->setField('type', 'tx_bmfbudgetexportrestsolr_searchresult-' . $structure);
        $documentForIndexSearch->setField('appKey', 'EXT:solr');

        $site = $this->siteRepository->getSiteByPageId(2); // TODO: Magic number

        // site, siteHash
        $documentForIndexSearch->setField('site', $site->getDomain());
        $documentForIndexSearch->setField('siteHash', $site->getSiteHash());

        // uid, pid
        $documentForIndexSearch->setField('uid', '2');
        $documentForIndexSearch->setField('pid', '0');

        // variantId
        $documentForIndexSearch->setField(
            'variantId',
            'tx_bmfbudgetexportrestsolr_searchresult-' . $structure . '/' . $filenameService->getJsonPath()
        );

        $date = new \DateTime();

        // created, changed
        $documentForIndexSearch->setField(
            'created',
            $date->format('Y-m-d\TH:i:s\Z')
        );

        $documentForIndexSearch->setField(
            'changed',
            $date->format('Y-m-d\TH:i:s\Z')
        );

        $documentForIndexSearch->setField('url', $filenameService->getHtmlUrl());
        $documentForIndexSearch->setField('title', $pageTitle);
        $documentForIndexSearch->setField('content', $teaser);

        // budget Address: for timeline indexer
        $timelineIdentifier = strtolower($data['detail']['type']) !== 'root' ? $address : 'root';
        if (strlen($timelineIdentifier) !== 9) {
            $timelineIdentifier = $structure . '-' . $timelineIdentifier;
        }
        $documentForIndexSearch->setField('timelineIdentifier', $timelineIdentifier);

        return $documentForIndexSearch;
    }


    /**
     * Removes content that shouldn't be indexed according to TYPO3SEARCH-tags.
     *
     * @param string HTML Content, passed by reference
     * @return bool Returns TRUE if a TYPOSEARCH_ tag was found, otherwise FALSE.
     * @todo Define visibility
     */
    public function typoSearchTags(&$body)
    {
        $expBody = preg_split('/\\<\\!\\-\\-[\\s]?TYPO3SEARCH_/', $body);
        if (count($expBody) > 1) {
            $body = '';
            $prev = '';
            foreach ($expBody as $val) {
                $part = explode('-->', $val, 2);
                if (trim($part[0]) === 'begin') {
                    $body .= $part[1];
                    $prev = '';
                } elseif (trim($part[0]) === 'end') {
                    $body .= $prev;
                } else {
                    $prev = $val;
                }
            }
            return true;
        }
        return false;
    }

    protected function getRootPath(
        $extbaseFrameworkConfiguration = null,
        $rootPath = ''
    ) {
        return str_replace(
            'Backend/',
            '',
            GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view'][$rootPath][0])
        );
    }
}
