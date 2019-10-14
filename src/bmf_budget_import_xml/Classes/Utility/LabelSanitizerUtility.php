<?php
namespace PPK\BmfBudgetImportXml\Utility;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class LabelSanitizerUtility
{
    /**
     * Converts all whitespaces (line breaks, more than one space, tabs) to single spaces in given label
     *
     * @param string $label
     * @return string Sanitized label
     */
    public static function sanitize(string $label) : string
    {
        $sanitized = str_replace(["\n", "\r", "\t", '  '], ' ', $label);
        $sanitized = str_replace('  ', ' ', $sanitized);
        return trim($sanitized);
    }
}
