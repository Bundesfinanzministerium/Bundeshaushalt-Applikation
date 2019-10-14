<?php
namespace PPKOELN\BmfBudget\Service\Page;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class TitleService extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    public function getPageTitle($result = [], $referrer = [])
    {
        $pageTitle = '';
        if ($result === null) {
            $pageTitle = 'Die Struktur des Bundeshaushaltes';
        } elseif (strtolower($result['type']) === 'root') {
            $pageTitle = LocalizationUtility::translate('seo.structures.' . $referrer['structure'], 'bmf_budget') . ' '
                    . $referrer['year']
                    . ' - Übersicht '
                    . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget') . ' '
                    . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget');
        } else {
            $address = $result['entity']->getAddress();
            $title = $result['entity']->getTitle();
            $pageTitle = LocalizationUtility::translate(
                'seo.structure.' . $referrer['structure'],
                'bmf_budget'
            ) . ' '
                    . $referrer['year'] . ', '
                    . LocalizationUtility::translate(
                        'seo.account.' . $referrer['account'],
                        'bmf_budget'
                    ) . ' - '
                    . LocalizationUtility::translate(
                        'seo.flow.' . $referrer['flow'],
                        'bmf_budget'
                    ) . ' #'
                    . $address . ' - ' . $title;
        }
        return $pageTitle;
    }

    public function set($result = [], $referrer = [])
    {

        if ($result === null) {
            $pageTitle = $this->getPageTitle($result, $referrer);
            $pageDescription = 'Visualisierte Darstellung aller Ausgaben und Einnahmen des Bundeshaushalts 2012 bis ' .
                               '2017 in Einzelplänen, Gruppen und Funktionen';
            $pageKeywords = 'Bundeshaushalt,2012,2013,2014,2015,2016,2017,Ausgaben,Einnahmen,Übersicht,Einzelplan,' .
                            'Gruppe,Funktion';
            $GLOBALS['TSFE']->page['title'] = $pageTitle;
            $GLOBALS['TSFE']->indexedDocTitle = $pageTitle;
            $GLOBALS['TSFE']->page['description'] = $pageDescription;
            $GLOBALS['TSFE']->page['keywords'] = $pageKeywords;
        } elseif (strtolower($result['type']) === 'root') {
            $pageTitle = $this->getPageTitle($result, $referrer);
            $GLOBALS['TSFE']->page['title'] = $pageTitle;
            $GLOBALS['TSFE']->indexedDocTitle = $pageTitle;

            $GLOBALS['TSFE']->page['description'] = 'Übersicht aller '
                . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget') . ' - '
                . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget') . ' in '
                . LocalizationUtility::translate('seo.structures.' . $referrer['structure'], 'bmf_budget')
                . ' des Bundeshaushalts '
                . $referrer['year'];

            $GLOBALS['TSFE']->page['keywords'] = 'Bundeshaushalt'
                . ',' . $referrer['year']
                . ',' . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget')
                . ',' . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget')
                . ',' . LocalizationUtility::translate('seo.structures.' . $referrer['structure'], 'bmf_budget')
                . ',Übersicht';
        } else {
            $pageTitle = $this->getPageTitle($result, $referrer);

            $address = $result['entity']->getAddress();
            $title = $result['entity']->getTitle();

            $GLOBALS['TSFE']->page['title'] = $pageTitle;
            $GLOBALS['TSFE']->indexedDocTitle = $pageTitle;

            $GLOBALS['TSFE']->page['description'] = 'Detailinformation des Postens \''
                . $title . '\' mit der Haushaltsstelle '
                . $address . ' als '
                . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget') . ' - '
                . LocalizationUtility::translate('seo.flow.' . $referrer['flow'], 'bmf_budget');

            $GLOBALS['TSFE']->page['keywords'] = 'Bundeshaushalt'
                . ',' . $referrer['year']
                . ',' . LocalizationUtility::translate('seo.account.' . $referrer['account'], 'bmf_budget')
                . ',' . LocalizationUtility::translate('seo.flows.' . $referrer['flow'], 'bmf_budget')
                . ',' . LocalizationUtility::translate('seo.structures.' . $referrer['structure'], 'bmf_budget')
                . ',' . $address
                . ',' . $title;
        }

        return null;
    }
}
