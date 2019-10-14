<?php
namespace PPKOELN\BmfPageBundeshaushaltInfo\Tracking;

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Tracker
{
    /**
     * piwik Config
     * central piwik hosts config
     *
     * @var array
     */
    protected $piwikConfig = [
        'Development/vm-bmf-bundeshaushalt' => [
            'uri' => '//piwik.bundeshaushalt.vbox/',
            'path' => 'piwik/',
            'domains' => '*.bundeshaushalt.vbox',
            'idSite' => '1'
        ],
        'Testing' => [
            'uri' => '//analytics.bundesfinanzministerium.de',
            'path' => '/',
            'domains' => '*.test-web02-bundeshaushalt-info.pixelpark.net',
            'idSite' => '94'
        ],
        'Production' => [
            'uri' => '//analytics.bundesfinanzministerium.de',
            'path' => '/',
            'domain' => '*.bundeshaushalt.de',
            'idSite' => '43'
        ]
    ];

    /**
     * @param $s
     * @return string
     */
    protected function getProtocol($s)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . ($ssl ? 's' : '');

        return $protocol;
    }

    /**
     * get host & protocol
     *
     * @param $s
     * @return string
     */
    protected function getHost($s)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $protocol = $this->getProtocol($s);
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    /**
     * trackPageView
     *
     * wrapper function for tracking the page view:
     *      automatically finds the correct configuration for the current environment
     *      tracks request uri, may be overridden by a certain document title
     *
     * @param string $documentTitle
     */
    public function trackPageView($documentTitle = '')
    {
        if (array_key_exists($_SERVER['TYPO3_CONTEXT'], $this->piwikConfig)) {
            try {
                $trackingUrl =
                    $this->getHost($_SERVER) . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
                if ($documentTitle === '') {
                    $documentTitle = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
                }
                if (!class_exists(PiwikTracker::class)) {
                    // we have to include PiwikTracker here when we want to use it in the send_rest php files
                    require_once __DIR__ . '/PiwikTracker.php';
                }
                PiwikTracker::$URL =
                    $this->getProtocol($_SERVER) . ':' . $this->piwikConfig[$_SERVER['TYPO3_CONTEXT']]['uri'] .
                    $this->piwikConfig[$_SERVER['TYPO3_CONTEXT']]['path'];

                $piwikTracker = new PiwikTracker($this->piwikConfig[$_SERVER['TYPO3_CONTEXT']]['idSite']);
                // take request uri as explicit url, otherwise we will get all custom realurl mapped query params
                $piwikTracker->setUrl($trackingUrl);
                $piwikTracker->setRequestTimeout(10);
                $piwikTracker->doTrackPageView($documentTitle);
            } catch (\Exception $e) {
                // simply do nothing, we don't want the application to die when something went wrong
                // sending the piwik tracking request
            }
        }
    }

    public function showTrackingSnippet()
    {
        $output = '';

        if (array_key_exists($_SERVER['TYPO3_CONTEXT'], $this->piwikConfig)) {
            $piwikConfig = $this->piwikConfig[$_SERVER['TYPO3_CONTEXT']];

            $output = '
<!-- Piwik -->
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(["setDomains", ["' . $piwikConfig['domains'] . '"]]);
    _paq.push(["trackPageView"]);
    _paq.push(["enableLinkTracking"]);
    (function() {
        var u="' . $piwikConfig['uri'] . $piwikConfig['path'] . '";
        _paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
        _paq.push([\'setSiteId\', \'' . $piwikConfig['idSite'] . '\']);
        var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
        g.type=\'text/javascript\'; g.async=true; g.defer=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);
    })();
</script>
<noscript><p><img src="' . $piwikConfig['uri'] . $piwikConfig['path'] . 'piwik.php?idsite=' . $piwikConfig['idSite'] .
                '&rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->';
        }
        return $output;
    }
}
