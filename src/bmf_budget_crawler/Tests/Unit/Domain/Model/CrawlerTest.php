<?php
namespace PPKOELN\BmfBudgetCrawler\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case for class \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler.
 */
class CrawlerTest extends UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getSessionReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getSession()
        );
    }

    /**
     * @test
     */
    public function setSessionForStringSetsSession()
    {
        $this->subject->setSession('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'session',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getExtClassReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getExtClass()
        );
    }

    /**
     * @test
     */
    public function setExtClassForStringSetsExtClass()
    {
        $this->subject->setExtClass('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'extClass',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getProgressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getProgress()
        );
    }

    /**
     * @test
     */
    public function setProgressForStringSetsProgress()
    {
        $this->subject->setProgress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'progress',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getQueuesReturnsInitialValueForQueue()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getQueues()
        );
    }

    /**
     * @test
     */
    public function setQueuesForObjectStorageContainingQueueSetsQueues()
    {
        $queue = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
        $objectStorageHoldingExactlyOneQueue = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneQueue->attach($queue);
        $this->subject->setQueues($objectStorageHoldingExactlyOneQueue);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneQueue,
            'queues',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addQueueToObjectStorageHoldingQueues()
    {
        $queue = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
        $queueObjectStorageMock = $this->getMock(
            ObjectStorage::class,
            ['attach'],
            [],
            '',
            false
        );
        $queueObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($queue));
        $this->inject($this->subject, 'queues', $queueObjectStorageMock);

        $this->subject->addQueue($queue);
    }

    /**
     * @test
     */
    public function removeQueueFromObjectStorageHoldingQueues()
    {
        $queue = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
        $queueObjectStorageMock = $this->getMock(
            ObjectStorage::class,
            ['detach'],
            [],
            '',
            false
        );
        $queueObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($queue));
        $this->inject($this->subject, 'queues', $queueObjectStorageMock);

        $this->subject->removeQueue($queue);
    }
}
