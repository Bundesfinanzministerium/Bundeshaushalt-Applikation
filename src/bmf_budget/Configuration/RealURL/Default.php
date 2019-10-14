<?php
/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

// phpcs:disable
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['encodeSpURL_postProc'][] = 'user_encodeSpURL_postProc';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['decodeSpURL_preProc'][] = 'user_decodeSpURL_preProc';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT']['application'] = [
    ['GETvar' => 'tx_bmfbudget_pi1[controller]'],
    ['GETvar' => 'tx_bmfbudget_pi1[action]'],
    ['GETvar' => 'tx_bmfbudget_pi1[year]'],
    ['GETvar' => 'tx_bmfbudget_pi1[account]'],
    ['GETvar' => 'tx_bmfbudget_pi1[flow]'],
    ['GETvar' => 'tx_bmfbudget_pi1[structure]'],
    ['GETvar' => 'tx_bmfbudget_pi1[address]']
];

function user_encodeSpURL_postProc(&$params, &$ref)
{

    if (preg_match('/^application\/Budget\/show\//i', $params['URL'])) {
        $params['URL'] = str_replace('application/Budget/show/', '', $params['URL']);
    }
}

function user_decodeSpURL_preProc(&$params, &$ref)
{

    if (preg_match('/^20\d{2}\/(target|actual)\/(income|expenses)\/(section|group|function)/i', $params['URL'])) {
        $params['URL'] = 'application/Budget/show/' . $params['URL'];

        /** @var \TYPO3\CMS\Frontend\Page\CacheHashCalculator $cacheHashCalculator */
        $cacheHashCalculator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(CacheHashCalculator::class);

        $cacheHashCalculator->setConfiguration(
            [
            'requireCacheHashPresenceParameters' => [
                'tx_bmfbudget_pi1[controller]',
                'tx_bmfbudget_pi1[action]',
                'tx_bmfbudget_pi1[year]',
                'tx_bmfbudget_pi1[account]',
                'tx_bmfbudget_pi1[flow]',
                'tx_bmfbudget_pi1[structure]',
                'tx_bmfbudget_pi1[address]'
            ]
            ]
        );
    } elseif (preg_match('/^20\d{2}\/(soll|ist)\/(einnahmen|ausgaben)\/(einzelplan|gruppe|funktion)/i', $params['URL'])
    ) {
        $params['URL'] = 'application/Budget/show/' . $params['URL'];

        /** @var \TYPO3\CMS\Frontend\Page\CacheHashCalculator $cacheHashCalculator */
        $cacheHashCalculator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(CacheHashCalculator::class);

        $cacheHashCalculator->setConfiguration(
            [
            'requireCacheHashPresenceParameters' => [
                'tx_bmfbudget_pi1[controller]',
                'tx_bmfbudget_pi1[action]',
                'tx_bmfbudget_pi1[year]',
                'tx_bmfbudget_pi1[account]',
                'tx_bmfbudget_pi1[flow]',
                'tx_bmfbudget_pi1[structure]',
                'tx_bmfbudget_pi1[address]'
            ]
            ]
        );
    }
}
// phpcs:enable
