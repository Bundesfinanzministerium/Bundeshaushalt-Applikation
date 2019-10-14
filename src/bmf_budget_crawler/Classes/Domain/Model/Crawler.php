<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Model;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Crawler extends AbstractEntity
{
    /**
     * Extension title
     *
     * @var string
     */
    protected $extTitle = '';

    /**
     * extClass
     *
     * @var string
     */
    protected $extClass = '';

    /**
     * Selected budget
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     * @validate NotEmpty
     */
    protected $budget;

    /**
     * Selected account
     *
     * @var string
     * @validate NotEmpty
     */
    protected $account = '';

    /**
     * Selected flow
     *
     * @var string
     * @validate NotEmpty
     */
    protected $flow = '';

    /**
     * Selected structure
     *
     * @var string
     * @validate NotEmpty
     */
    protected $structure = '';

    /**
     * preprocessed
     *
     * @var boolean
     */
    protected $preprocessed = false;

    /**
     * progress
     *
     * @var float
     */
    protected $progress = 0;

    /**
     * queues
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue>
     * @lazy
     * @cascade remove
     */
    protected $queues;

    /**
     * error
     *
     * @var integer
     */
    protected $error = 0;

    /**
     * error message
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Process start
     *
     * @var \DateTime
     */
    protected $processStart;

    /**
     * Process end
     *
     * @var \DateTime
     */
    protected $processEnd;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->queues = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the extension title
     *
     * @return string $extTitle
     */
    public function getExtTitle()
    {
        return $this->extTitle;
    }

    /**
     * Sets the extension title
     *
     * @param string $extTitle
     * @return void
     */
    public function setExtTitle($extTitle)
    {
        $this->extTitle = $extTitle;
    }

    /**
     * Returns the extClass
     *
     * @return string $extClass
     */
    public function getExtClass()
    {
        return $this->extClass;
    }

    /**
     * Sets the extClass
     *
     * @param string $extClass
     * @return void
     */
    public function setExtClass($extClass)
    {
        $this->extClass = $extClass;
    }

    /**
     * Returns the budget
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets the budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     * @return void
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * Returns the account configuration
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets the account configuration
     *
     * @param string $account
     * @return void
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * Returns the flow configuration
     *
     * @return string
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * Sets the flow configuration
     *
     * @param string $flow
     * @return void
     */
    public function setFlow($flow)
    {
        $this->flow = $flow;
    }

    /**
     * Returns the structure configuration
     *
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Sets the structure configuration
     *
     * @param string $structure
     * @return void
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    /**
     * Returns if crawler is already pre-processed
     *
     * @return bool
     */
    public function isPreprocessed()
    {
        return $this->preprocessed;
    }

    /**
     * Returns if crawler is already pre-processed
     *
     * @return float $progress
     */
    public function getPreprocessed()
    {
        return $this->preprocessed;
    }

    /**
     * Sets crawler is already pre-processed
     *
     * @param bool $preprocessed
     */
    public function setPreprocessed($preprocessed)
    {
        $this->preprocessed = $preprocessed;
    }

    /**
     * Returns the progress
     *
     * @return float $progress
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Sets the progress
     *
     * @param float $progress
     * @return void
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /**
     * Adds a queue
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue
     * @return void
     */
    public function addQueue(\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue)
    {
        $this->queues->attach($queue);
    }

    /**
     * Removes a queue
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queues ToRemove The Queue to be removed
     * @return void
     */
    public function removeQueue(\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue $queue)
    {
        $this->queues->detach($queue);
    }

    /**
     * Returns the queues
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue> $queues
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * Sets the queues
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudgetCrawler\Domain\Model\Queue> $queues
     * @return void
     */
    public function setQueues(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $queues)
    {
        $this->queues = $queues;
    }

    /**
     * Returns the error
     *
     * @return integer $error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the error
     *
     * @param integer $error
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Returns the error message
     *
     * @return string $errorMessage
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets the error message
     *
     * @param string $errorMessage
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Returns the process start time
     *
     * @return \DateTime
     */
    public function getProcessStart()
    {
        return $this->processStart;
    }

    /**
     * Sets the process start time
     *
     * @param \DateTime $processStart
     */
    public function setProcessStart($processStart)
    {
        $this->processStart = $processStart;
    }

    /**
     * Returns the process end time
     *
     * @return \DateTime
     */
    public function getProcessEnd()
    {
        return $this->processEnd;
    }

    /**
     * Sets the process end time
     *
     * @param \DateTime $processEnd
     */
    public function setProcessEnd($processEnd)
    {
        $this->processEnd = $processEnd;
    }
}
