<?php
namespace PPKOELN\BmfBudget\Service\Backend;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class LogService extends \TYPO3\CMS\Core\Service\AbstractService implements \TYPO3\CMS\Core\SingletonInterface
{
    protected $session = '';
    protected $file = '';
    protected $start = 0;

    public function initialize($session)
    {
        $this->file = PATH_site . 'typo3temp/bmfprocess-' . $session . '.js';
        $this->registerTempFile($this->file);

        if (!is_file($this->file)) {
            touch($this->file);
        }

        clearstatcache(null, $this->file);
        $this->start = time();
    }

    public function write(array $param = [])
    {
        $content = '{"memory" : "' . memory_get_usage() . '", ' .
            '"duration" : "' . (time() - $this->start) .'", ' .
            '"content"  : ' . json_encode($param)  . '}';

        $this->writeFile($content, $this->file);
    }

    public function close(array $param = [])
    {
        $content = '{"memory" : "' . memory_get_usage() . '", ' .
            '"duration" : "' . (time() - $this->start) .'", ' .
            '"done":"1", ' .
            '"content"  : ' . json_encode($param)  . '}';

        $this->writeFile($content, $this->file);
    }
}
