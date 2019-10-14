<?php
namespace PPKOELN\BmfBudgetCrawler\Service;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Service\AbstractService;
use \TYPO3\CMS\Core\SingletonInterface;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractHookService extends AbstractService implements SingletonInterface
{
    /**
     * @var array
     */
    protected $service;

    abstract public function __construct();

    /**
     * @return array
     */
    public function getHooksAsArray()
    {
        $retVal = [];
        if (count($this->service) > 0) {
            foreach ($this->service as $hook) {
                /** @var \PPKOELN\BmfBudgetCrawler\Hook\HookInterface $class */
                $class = GeneralUtility::makeInstance($hook);
                $retVal[] = $class->getTitle();
            }
        }
        return $retVal;
    }

    /**
     * @param int $index
     * @return bool|mixed
     */
    public function getHooksByIndex($index = 0)
    {
        $hooks = $this->getHooksAsObjects();
        return isset($hooks[$index]) ? $hooks[$index] : false;
    }

    /**
     * @return array
     */
    protected function getHooksAsObjects()
    {
        $retVal = [];
        $count = 0;

        if (count($this->service) > 0) {
            foreach ($this->service as $hook) {
                /** @var \PPKOELN\BmfBudgetCrawler\Hook\HookInterface $class */
                $class = GeneralUtility::makeInstance($hook);

                $obj = new \stdClass();
                $obj->title = $class->getTitle();
                $obj->class = $hook;

                $retVal[$count++] = $obj;
            }
        }
        return $retVal;
    }
}
