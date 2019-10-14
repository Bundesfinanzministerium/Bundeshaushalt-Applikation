<?php
namespace PPKOELN\BmfBudget\ViewHelpers;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class PropertyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('object', 'object', '', true);
        $this->registerArgument('property', 'string', '', true);
    }

    /**
     * Main method
     *
     * @return bool|string
     */
    public function render()
    {
        $arr = explode('_', $this->arguments['property']);
        $get = 'get';
        $res = false;

        foreach ($arr as $value) {
            $get .= ucfirst($value);
        }

        if (method_exists($this->arguments['object'], $get)) {
            $res = $this->arguments['object']->$get();
        }
        return $res !== false && $res !== null ? (string)$res : '';
    }
}
