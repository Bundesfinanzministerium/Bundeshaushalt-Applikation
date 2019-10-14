<?php
namespace PPKOELN\BmfBudgetApplicationEmbed\ViewHelpers;

/*
 * This file is part of the "bmf_budget_application_embed" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class DisplayObjectViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('object', 'string', '', true);
    }

    /**
    * This helper only show object values
    * it is user within JSon Object cause fluid runs into trouble with {}
    *
    * @return string the rendered string
    */
    public function render()
    {
        $val = str_replace([chr(13), "\n", '"'], [' ', ' ', '\"'], $this->arguments['object']);
        $val = preg_replace('#\s+#', ' ', $val);
        return $val;
    }
}
