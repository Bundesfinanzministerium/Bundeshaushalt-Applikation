<?php
// phpcs:disable

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class tx_bmfbudget_value
{
    /**
     * Javascript validation
     *
     * @return string
     */
    public function returnFieldJS()
    {
        return 'return value !== "" ? parseFloat(value.replace(",", ".")).toFixed(2) : "";';
    }

    /**
     * Prepare value for commit
     *
     * @param string $value Parameter to prepare
     * @return null|string
     */
    public function evaluateFieldValue($value = '')
    {
        $value = str_replace(', ', '.', $value);

        return is_numeric($value) ? number_format((float)$value, 2, '.', '') : null;
    }
}
// phpcs:enable
