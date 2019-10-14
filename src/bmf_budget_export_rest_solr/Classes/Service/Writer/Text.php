<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Writer;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use PPKOELN\BmfBudget\Domain\Model\Budget;

class Text
{
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
     * @var \ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository
     * @inject
     */
    protected $siteRepository;

    /**
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

        /** @var \Apache_Solr_Document $documentForIndexSearch */
        $documentForIndexSearch = GeneralUtility::makeInstance('Apache_Solr_Document');

        // required fields
        $documentForIndexSearch->setField(
            'id',
            \ApacheSolrForTypo3\Solr\Util::getDocumentId(
                'tx_bmfbudgetexportrestsolr_searchresult-' . $structure,
                2,
                $filenameService->getJsonPath()
            )
        );
        $documentForIndexSearch->setField('type', 'tx_bmfbudgetexportrestsolr_searchresult-' . $structure);
        $documentForIndexSearch->setField('appKey', 'EXT:solr');

        $site = $this->siteRepository->getSiteByPageId(2); // TODO: Magic Number

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

        $documentForIndexSearch->setField(
            'url',
            $filenameService->getHtmlUrl()
        );

        $documentForIndexSearch->setField(
            'title',
            $data['detail']['entity']->getTitle()
        );

        $documentForIndexSearch->setField(
            'content',
            $this->getContent($data['detail'], $referrer)
        );

        // budget Address: for timeline indexer
        $timelineIdentifier = strtolower($data['detail']['type']) !== 'root' ? $address : 'root';
        if (strlen($timelineIdentifier) != 9) {
            $timelineIdentifier = $structure . '-' . $timelineIdentifier;
        }
        $documentForIndexSearch->setField('timelineIdentifier', $timelineIdentifier);

        return $documentForIndexSearch;
    }


    public function getContent(
        $result = [],
        $referrer = []
    ) {

        $content = '';

        if ($result === null) {
            $content = 'Die Struktur des Bundeshaushaltes';
        } elseif (strtolower($result['type']) === 'root') {
            $content = LocalizationUtility::translate('seo.structures.' . $referrer['structure'], 'bmf_budget')
                . ' '
                . $referrer['year']
                . ' - Übersicht '
                . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget') . ' '
                . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget');
        } else {
            $address = $result['entity']->getAddress();
            $title = $result['entity']->getTitle();

            $content = LocalizationUtility::translate('seo.structure.' . $referrer['structure'], 'bmf_budget')
                . ' '
                . $referrer['year'] . ', '
                . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget')
                . ' - '
                . LocalizationUtility::translate('seo.flow.' . $referrer['flow'], 'bmf_budget')
                . ' #' . $address . ' - ' . $title;
        }

        if ($revenue = $result['entity']->getRevenue($referrer['account'], $referrer['flow'])) {
            $revenue = strtolower($referrer['account']) === 'actual'
                ? intval($revenue / 1000)
                : intval($revenue);

            $revenue = number_format(
                $revenue,
                0,
                ',',
                '.'
            );

            $content .= ', '
                . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget')
                . ' '
                . LocalizationUtility::translate('inThousandsOfEuros', 'bmf_budget')
                . ': ' . $revenue;
        }

        return $content;
    }
}
