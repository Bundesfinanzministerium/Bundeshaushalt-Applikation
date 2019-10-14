<?php
namespace PPKOELN\BmfPageBundeshaushaltInfo\Controller;

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller for Slick Slider Container (enhanced Accordion functionality)
 */
class SlickSliderController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @return void
     */
    public function showAction()
    {
        $contentElements = [];
        $contentElementUids = GeneralUtility::trimExplode(',', $this->settings['contentelements'], true);
        foreach ($contentElementUids as $contentElementUid) {
            $item = $this->getContentElementByUid($contentElementUid);
            if (!empty($item)) {
                $contentElements[$contentElementUid] = $item;
            }
        }
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);
        $this->view->assign('contentElements', $contentElements);
    }

    /**
     * @param int $uid
     * @return array tt_content row or empty array, if not found
     */
    protected function getContentElementByUid(int $uid) : array
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');

        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('uid', $uid))
            ->execute()
            ->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
