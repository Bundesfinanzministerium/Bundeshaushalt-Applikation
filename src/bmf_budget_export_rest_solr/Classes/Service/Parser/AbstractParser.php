<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Service\AbstractService;

class AbstractParser extends AbstractService
{
    protected function getCurrentLevel($structure = '', $address = '')
    {
        /**
         * Return parameter
         */
        $retval = 0;

        switch (strtolower($structure)) {
            case "section":
                if (strlen($address) == 2) {
                    $retval = 1;
                } elseif (strlen($address) >= 4) {
                    $retval = 2;
                }
                break;
            case "function":
                if (strlen($address) == 1) {
                    $retval = 1;
                } elseif (strlen($address) == 2) {
                    $retval = 2;
                } elseif (strlen($address) >= 3) {
                    $retval = 3;
                }
                break;
            case "group":
                if (strlen($address) == 1) {
                    $retval = 1;
                } elseif (strlen($address) == 2) {
                    $retval = 2;
                } elseif (strlen($address) >= 3) {
                    $retval = 3;
                }
                break;
            default:
        }

        return $retval;
    }

    /**
     * Returns the max level of depending structure
     *
     * @param string $structure Corresponding structure
     * @return bool|int
     */
    protected function getMaxLevel($structure = '')
    {
        /**
         * Return parameter
         */
        $retval = 0;

        switch (strtolower($structure)) {
            case "section":
                $retval = 2;
                break;
            case "function":
                $retval = 3;
                break;
            case "group":
                $retval = 3;
                break;
            default:
        }
        return (int)$retval > 0 ? $retval : false;
    }

    protected function formatAddress($entity)
    {
        $retval = '';
        if ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Title) {
            $retval = $this->formatTitle($entity->getAddress(), $entity->getFunctione()->getAddress());
        } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Section) {
            $retval = $this->formatSection($entity->getAddress());
        } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Functione) {
            $retval = $this->formatFunction($entity->getAddress());
        } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Groupe) {
            $retval = $this->formatGroup($entity->getAddress());
        }

        return $retval;
    }

    protected function formatTitle($addTitel, $addFunktion)
    {
        $ep = substr($addTitel, 0, 4);
        $gp = substr($addTitel, 4, 3);
        $ln = substr($addTitel, 7, 2);

        return '<strong>' . $ep . ' ' . $gp . ' ' . $ln . ' - ' . $addFunktion . '</strong>';
    }

    protected function formatSection($address)
    {
        if ($address == '--global--') {
            $address = '';
        }
        $ep = strlen($address) == 4 ? '<strong>' . $address . '</strong>' : '';
        $ep = strlen($address) == 2 ? '<strong>' . $address . '</strong>__' : $ep;
        $ep = strlen($address) == 0 ? '____' : $ep;

        return $ep . ' ___ __ - ___';
    }

    protected function formatGroup($address)
    {
        if ($address == '--global--') {
            $address = '';
        }
        $ep = strlen($address) == 3 ? '<strong>' . $address . '</strong>' : '';
        $ep = strlen($address) == 2 ? '<strong>' . $address . '</strong>_' : $ep;
        $ep = strlen($address) == 1 ? '<strong>' . $address . '</strong>__' : $ep;
        $ep = strlen($address) == 0 ? '___' : $ep;

        return '____ ' . $ep . ' __ - ___';
    }

    protected function formatFunction($address)
    {
        if ($address == '--global--') {
            $address = '';
        }
        $ep = strlen($address) == 3 ? '<strong>' . $address . '</strong>' : '';
        $ep = strlen($address) == 2 ? '<strong>' . $address . '</strong>_' : $ep;
        $ep = strlen($address) == 1 ? '<strong>' . $address . '</strong>__' : $ep;
        $ep = strlen($address) == 0 ? '___' : $ep;

        return '____ ___ __ - ' . $ep;
    }

    /**
     * Get JSON Details of title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Title|NULL $title
     * @param string $account
     *
     * @return array
     */
    protected function getTitelDetails(
        \PPKOELN\BmfBudget\Domain\Model\Title $title = null,
        $account = ''
    ) {

        $retval = [];

        $retval['ep'] = [
            'a' => $title->getSection()->getSection()->getAddress(),
            'l' => $title->getSection()->getSection()->getTitle()
        ];

        $retval['kp'] = [
            'a' => $title->getSection()->getAddress(),
            'l' => $title->getSection()->getTitle()
        ];

        if ($title->getBudgetgroup() !== null) {
            $retval['ug'] = $title->getBudgetgroup()->getTitle();
        } elseif ($title->getTitlegroup() !== null) {
            $retval['tg'] = [
                'a' => $title->getTitlegroup()->getAddress(),
                'l' => $title->getTitlegroup()->getTitle()
            ];
        }

        if ($account == 'target') {
            $retval['pdf'] = (string)$title->getTargetPage();
            $retval['pdfL'] = (string)$title->getTargetPageLink();
            if (count($title->getSupplementaries()) > 0) {
                foreach ($title->getSupplementaries() as $supplementary) {
                    /** @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementary */
                    $retval['pdfN' . substr(
                        $supplementary->getSupplementarybudget()->getTitle(),
                        0,
                        1
                    )] = (string)$supplementary->getTargetPage();
                }
            }
        } elseif ($account == 'actual') {
            $retval['pdf'] = (string)$title->getActualPage();
            $retval['pdfL'] = (string)$title->getActualPageLink();
        }

        return $retval;
    }

    protected function removeLn($argument)
    {
        $argument = str_replace("\r", '', $argument);
        $argument = str_replace("\n", '', $argument);
        return $argument;
    }
}
