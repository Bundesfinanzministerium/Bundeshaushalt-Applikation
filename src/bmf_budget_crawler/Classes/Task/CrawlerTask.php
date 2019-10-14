<?php
namespace PPKOELN\BmfBudgetCrawler\Task;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\ProgressProviderInterface;

class CrawlerTask extends AbstractTask implements ProgressProviderInterface
{
    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Crawler Repository
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository
     */
    protected $crawlerRepository;

    /**
     * Queue Repository
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\QueueRepository
     */
    protected $queueRepository;

    /**
     * Crawler which is currently in progress
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler
     */
    protected $inProgressCrawler;

    /**
     * CrawlerTask constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initializeClass();
    }

    protected function initializeClass()
    {
        if ($this->objectManager !== null) {
            return null;
        }

        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Object\ObjectManager::class
        );

        $this->persistenceManager = $this->objectManager->get(
            \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface::class
        );

        $this->crawlerRepository = $this->objectManager->get(
            \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository::class
        );

        $this->queueRepository = $this->objectManager->get(
            \PPKOELN\BmfBudgetCrawler\Domain\Repository\QueueRepository::class
        );

        $this->inProgressCrawler = $this->crawlerRepository->findInprogressJob();
    }

    /**
     * @return bool
     */
    public function execute()
    {
        /**
         * Initial class
         */
        $this->initializeClass();

        /**
         * exit if no crawler is in progress
         */
        if (!$this->inProgressCrawler) {
            return true;
        }

        /**
         * Initialize Typoscript Frontend
         */
        $this->initializeTSFE(2, 'de');

        /**
         * Check if crawler needs to pre-processed
         */
        if (!$this->inProgressCrawler->getPreprocessed()) {
            $this->prepareQueue($this->inProgressCrawler);
        } else {
            $this->processQueue($this->inProgressCrawler);
        }

        return true;
    }

    /**
     * This method prepares the crawler queue's based on the configuration
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    protected function prepareQueue(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        $processStart = new \DateTime();
        $extClass = $this->objectManager->get($crawler->getExtClass());

        try {
            $extClass->preProc($crawler);
            $crawler->setError(0);
            $crawler->setErrorMessage('');
        } catch (\Exception $e) {
            $crawler->setError(1);
            $crawler->setErrorMessage($e->getMessage());
        }

        $crawler->setProgress(5);
        $crawler->setPreprocessed(true);
        $crawler->setProcessStart($processStart);

        $this->crawlerRepository->update($crawler);
        $this->persistenceManager->persistAll();

        return null;
    }

    /**
     * This method process the crawler queue
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    protected function processQueue(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        /**
         * Get hooked class from crawler
         */
        $extClass = $this->objectManager->get($crawler->getExtClass());

        if ($queues = $this->queueRepository->findByCrawler(
            $crawler,
            (int)$this->numberOfProcessedQueues
        )
        ) {
            $result = '';
            $error = 0;

            $persistanceCounter = 0;
            $persistanceInterval = 50;

            /** @var \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue */
            foreach ($queues as $queue) {

                /**
                 * Process queue
                 */
                try {
                    $extClass->proc($queue, $result, $error);
                    $queue->setStatus(1);
                    $queue->setResult($result);
                    $queue->setError((int)$error);
                    $queue->setErrorMessage('');
                } catch (\Exception $e) {
                    $queue->setStatus(1);
                    $queue->setResult('');
                    $queue->setError(1);
                    $queue->setErrorMessage($e->getMessage());
                }

                /**
                 * Update queue
                 */
                $this->queueRepository->update($queue);

                /**
                 * Persist in interval
                 */
                $persistanceCounter++;
                if ($persistanceCounter % $persistanceInterval === 0) {
                    $this->persistenceManager->persistAll();
                }
            }
            $this->persistenceManager->persistAll();
        }

        /**
         * Update crawler
         */
        $totalQueues = $this->queueRepository->countByCrawler($crawler);
        $processedQueues = $this->queueRepository->countCrawled($crawler);

        $crawler->setProgress(
            $totalQueues > 0
                ? $processedQueues / $totalQueues * 100
                : 100
        );

        if ($crawler->getProgress() === 100) {
            $processEnd = new \DateTime();
            $crawler->setProcessEnd($processEnd);
        }

        $this->crawlerRepository->update($crawler);

        /**
         * Persist all changes
         */
        $this->persistenceManager->persistAll();

        return null;
    }

    /**
     * Initialize Typoscript Frontend
     *
     * @param int $pid Pageid
     * @param string $lang Language
     * @return null
     */
    protected function initializeTSFE($pid = 0, $lang = 'de')
    {
        $GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            $pid,
            0
        );

        \TYPO3\CMS\Frontend\Utility\EidUtility::initLanguage($lang);

        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();

        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TimeTracker\TimeTracker::class, false);
            $GLOBALS['TT']->start();
        }

        $GLOBALS['TSFE']->clear_preview();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        $GLOBALS['TSFE']->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class
        );

        $bootstrap = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Extbase\Core\Bootstrap::class
        );

        $bootstrap->initialize(
            [
            'extensionName' => 'BmfBudget',
            'pluginName' => 'Pi1',
            'vendorName' => 'PPKOELN'
            ]
        );

        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
            $rootline = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($pid);
            $host = \TYPO3\CMS\Backend\Utility\BackendUtility::firstDomainRecord($rootline);
            $_SERVER['HTTP_HOST'] = $host;
        }

        return null;
    }

    /**
     * Gets the indexing progress.
     *
     * @return float Indexing progress as a two decimal precision float. f.e. 44.87
     */
    public function getProgress()
    {
        /**
         * Initial class
         */
        $this->initializeClass();

        /**
         * return current progress
         */
        return $this->inProgressCrawler
            ? $this->inProgressCrawler->getProgress()
            : 100;
    }

    /**
     * Returns some additional information about indexing progress, shown in
     * the scheduler's task overview list.
     *
     * @return string Information to display
     */
    public function getAdditionalInformation()
    {
        /**
         * Initialize Typoscript Frontend
         */
        $this->initializeTSFE(2, 'de');

        /**
         * Initial class
         */
        $this->initializeClass();

        $processed = count($this->crawlerRepository->findByProgress(100));
        $complete = count($this->crawlerRepository->findAll());

        return 'Tasks in progress: '
            . ($complete - $processed)
            . ', total: ' . $complete . ' ( ' . (int)$this->numberOfProcessedQueues . ' per run )';
    }
}
