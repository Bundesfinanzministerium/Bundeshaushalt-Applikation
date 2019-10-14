<?php
namespace PPKOELN\BmfBudget\ViewHelpers;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class IfLogicalOrViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{
    /**
     * Initializes the "then" and "else" arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('then', 'mixed', 'Value to be returned if the condition if met.');
        $this->registerArgument('else', 'mixed', 'Value to be returned if the condition if not met.');
        $this->registerArgument(
            'condition1',
            'boolean',
            'Condition expression conforming to Fluid boolean rules',
            false,
            false
        );
        $this->registerArgument(
            'condition2',
            'boolean',
            'Condition expression conforming to Fluid boolean rules',
            false,
            false
        );
        $this->registerArgument(
            'condition3',
            'boolean',
            'Condition expression conforming to Fluid boolean rules',
            false,
            false
        );
        $this->registerArgument(
            'condition4',
            'boolean',
            'Condition expression conforming to Fluid boolean rules',
            false,
            false
        );
    }

    /**
     * @param null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        return (boolean) $arguments['condition1'] ||
               (boolean) $arguments['condition2'] ||
               (boolean) $arguments['condition3'] ||
               (boolean) $arguments['condition4'];
    }
}
