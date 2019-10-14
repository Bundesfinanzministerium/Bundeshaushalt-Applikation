<?php
namespace PPKOELN\BmfBudget\ViewHelpers\Math;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class PercentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('total', 'string', '', true);
        $this->registerArgument('value', 'string', '', true);
        $this->registerArgument('decimal', 'integer', '', false, 2);
    }

    /**
     * This helper only show object values
     * it is user within JSon Object cause fluid runs into trouble with {}
     *
     * @return string
     */
    public function render()
    {
        $value = $this->arguments['value'] > 0 ? $this->arguments['value'] : 0;
        $percent = $this->arguments['total'] > 0 ? (($value * 100) / $this->arguments['total']) : 0;
        return number_format(
            round($percent, (int) $this->arguments['decimal']),
            (int) $this->arguments['decimal'],
            ',',
            '.'
        );
    }
}
