<?php
namespace PPKOELN\BmfBudgetApplicationEmbed\Controller;

/*
 * This file is part of the "bmf_budget_application_embed" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmbedController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function initializeAction()
    {
        $settings = $this->settings;
        $script = "<script>var txPpBundeshaushaltPreDomain='" . $settings['serviceDomain'] . "';</script>\n";
        $this->response->addAdditionalHeaderData($script);
    }

    /**
     * @return void
     * http://bundeshaushalt.de.trunk.hardt.mobile/embed.html?
     * jahr=2012&               M�gliche Werte: 2012, 2013
     * plan=soll&               M�gliche Werte: soll, ist
     * typ=einnahmen&           M�gliche Werte: einnahmen, ausgaben
     * struktur=einzelplan&     M�gliche Werte: einzelplan, gruppe, funktion
     * adresse=&                M�gliche Werte: ...... (gaaaanz viele)
     * min&                     M�gliche Werte: 0, 1, 2, 3
     * delte                    M�gliche Werte: 0, 1, 2,        ehemals klickstopp=0
     */
    public function showAction()
    {
        $WHITE = [];
        $WHITE['error']['stat'] = false;
        $WHITE['error']['msg'] = '';

        $WHITE['yr'] = $this->checkYear(strtolower(GeneralUtility::_GP('jahr')), $WHITE);
        $WHITE['qt'] = $this->checkQuota(strtolower(GeneralUtility::_GP('plan')), $WHITE);
        $WHITE['tp'] = $this->checkTyp(strtolower(GeneralUtility::_GP('typ')), $WHITE);
        $WHITE['un'] = $this->checkStruktur(strtolower(GeneralUtility::_GP('struktur')), $WHITE);
        $WHITE['ad'] = $this->checkAdresse(GeneralUtility::_GP('adresse'), $WHITE);
        $WHITE['eb'] = $this->checkMin(GeneralUtility::_GP('min'), $WHITE);
        $WHITE['dt'] = $this->checkDelta(GeneralUtility::_GP('delta'), $WHITE);

        $this->view->assign('cnf', $WHITE);
    }

    public function checkYear($year, &$WHITE)
    {
        // setting up default value
        $return = '2019';

        if ($year === '2012') {
            return '2012';
        }
        if ($year === '2013') {
            return '2013';
        }
        if ($year === '2014') {
            return '2014';
        }
        if ($year === '2015') {
            return '2015';
        }
        if ($year === '2016') {
            return '2016';
        }
        if ($year === '2017') {
            return '2017';
        }
        if ($year === '2018') {
            return '2018';
        }
        if ($year === '2019') {
            return '2019';
        }

        return $return;
    }

    public function checkQuota($quota, &$WHITE)
    {
        // setting up default value
        $return = 'soll';

        if ($quota === 'soll') {
            return 'soll';
        }
        if ($quota === 'ist') {
            return 'ist';
        }

        return $return;
    }

    public function checkTyp($typ, &$WHITE)
    {
        // setting up default value
        $return = 'e';

        if ($typ === 'einnahmen') {
            return 'e';
        }
        if ($typ === 'ausgaben') {
            return 'a';
        }

        return $return;
    }

    public function checkStruktur($ansicht, &$WHITE)
    {
        // setting up default value
        $return = 'agency';

        if ($ansicht === 'einzelplan') {
            return 'agency';
        }
        if ($ansicht === 'gruppe') {
            return 'group';
        }
        if ($ansicht === 'funktion') {
            return 'function';
        }

        return $return;
    }

    public function checkAdresse($zeige, &$WHITE)
    {
        if ($WHITE['un'] === 'agency') {
            $res = $this->checkAddressEPL($zeige, $WHITE);
        }
        if ($WHITE['un'] === 'group') {
            $res = $this->checkAddressGRP($zeige, $WHITE);
        }
        if ($WHITE['un'] === 'function') {
            $res = $this->checkAddressFNK($zeige, $WHITE);
        }

        return $res;
    }

    public function checkMin($ebene, &$WHITE)
    {
        // initialize variables
        $return = 0;
        $ebene = (int) $ebene;

        if ($ebene >= 0 && $ebene <= $WHITE['maxLevel']) {
            return $ebene;
        }

        return $return;
    }

    public function checkDelta($klickstopp, &$WHITE)
    {
        // initialize variables
        $return = 0;
        $klickstopp = (int) $klickstopp;
        $diff = $WHITE['maxLevel'] - $WHITE['eb'];

        if ($klickstopp >= 0 && $klickstopp <= $diff) {
            return $klickstopp;
        }

        return $return;
    }

    public function checkAddressEPL($zeige, &$WHITE)
    {
        $WHITE['maxLevel'] = 3;

        if ($zeige === null) {
            $zeige = '';
        }
        if ($zeige === '') {
            return $zeige;
        }

        if (preg_match('/^([0-9]{2})$/', $zeige) || preg_match('/^([0-9]{4})$/', $zeige) ||
            preg_match('/^([0-9]{9})$/', $zeige)
        ) {
            return $zeige;
        }

        $WHITE['error'] = 'Einzeplan: Syntax ungültig';

        return false;
    }

    public function checkAddressGRP($zeige, &$WHITE)
    {
        $WHITE['maxLevel'] = 4;

        if ($zeige === null) {
            $zeige = '';
        }
        if ($zeige === '') {
            return $zeige;
        }

        if (preg_match('/^([0-9]{1})$/', $zeige) || preg_match('/^([0-9]{2})$/', $zeige) ||
            preg_match('/^([0-9]{3})$/', $zeige) || preg_match('/^([0-9]{9})$/', $zeige)
        ) {
            return $zeige;
        }

        $WHITE['error'] = 'Gruppe: Syntax ungültig';

        return false;
    }

    public function checkAddressFNK($zeige, &$WHITE)
    {
        $WHITE['maxLevel'] = 4;

        if ($zeige === null) {
            $zeige = '';
        }
        if ($zeige === '') {
            return $zeige;
        }

        if (preg_match('/^([0-9]{1})$/', $zeige) || preg_match('/^([0-9]{2})$/', $zeige) ||
            preg_match('/^([0-9]{3})$/', $zeige) || preg_match('/^([0-9]{9})$/', $zeige)
        ) {
            return $zeige;
        }

        $WHITE['error'] = 'Gruppe: Syntax ungültig';

        return false;
    }
}
