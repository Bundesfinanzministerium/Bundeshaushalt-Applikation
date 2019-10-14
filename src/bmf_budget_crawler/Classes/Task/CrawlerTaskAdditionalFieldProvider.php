<?php
namespace PPKOELN\BmfBudgetCrawler\Task;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;

class CrawlerTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * Number of processed queues per run
     *
     * @var int
     */
    protected $numberOfProcessedQueues = 0;

    /**
     * Default value of "Number of processed queues per run"
     *
     * @var int Default number of queues
     */
    protected $numberOfProcessedQueuesDefault = 500;

    /**
     * Field name of "Number of processed queues per run"
     *
     * @var string
     */
    protected $numberOfProcessedQueuesField = 'bmf_budget_crawler_numberOfElementsPerRun';

    /**
     * Add additional fields
     *
     * @param array $taskInfo
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
    ) {
        // Initialize selected fields
        if (!isset($taskInfo[$this->numberOfProcessedQueuesField])) {
            $taskInfo[$this->numberOfProcessedQueuesField] = $this->numberOfProcessedQueuesDefault;
            if ($parentObject->CMD === 'edit') {
                $taskInfo[$this->numberOfProcessedQueuesField] = $task->numberOfProcessedQueues;
            }
        }

        $fieldName = 'tx_scheduler[' . $this->numberOfProcessedQueuesField . ']';
        $fieldId = $this->numberOfProcessedQueuesField;
        $fieldValue = $taskInfo[$this->numberOfProcessedQueuesField];

        $fieldHtml = '<input type="text" name="'
            . $fieldName . '" id="' . $fieldId . '" value="'
            . htmlspecialchars($fieldValue) . '" />';

        $additionalFields[$fieldId] = [
            'code' => $fieldHtml,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang.xlf:'
                . 'task.label.numberOfProcessedQueues',
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldId
        ];

        return $additionalFields;
    }

    /**
     * Validate additional fields
     *
     * @param array $submittedData
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
     * @return string
     */
    public function validateAdditionalFields(
        array &$submittedData,
        \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
    ) {
        $result = true;

        if (!is_numeric($submittedData[$this->numberOfProcessedQueuesField])
            || (int)$submittedData[$this->numberOfProcessedQueuesField] < 0
        ) {
            $result = false;

            $parentObject->addMessage(
                $GLOBALS['LANG']->sL('LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang.xlf:'
                . 'task.error.numberOfProcessedQueues'),
                \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
            );
        }

        return $result;
    }

    /**
     * Save additional field in task
     *
     * @param array $submittedData Contains data submitted by the user
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the current task object
     * @return void
     */
    public function saveAdditionalFields(
        array $submittedData,
        \TYPO3\CMS\Scheduler\Task\AbstractTask $task
    ) {
        $task->numberOfProcessedQueues = (int)$submittedData[$this->numberOfProcessedQueuesField];
        return null;
    }
}
