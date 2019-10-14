<?php
namespace PPKOELN\BmfBudgetCrawler\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test case for class \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue.
 */
class QueueTest extends UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Queue();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getBudgetReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getBudget()
        );
    }

    /**
     * @test
     */
    public function setBudgetForIntegerSetsBudget()
    {
        $this->subject->setBudget(12);

        $this->assertAttributeEquals(
            12,
            'budget',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAccountReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAccount()
        );
    }

    /**
     * @test
     */
    public function setAccountForStringSetsAccount()
    {
        $this->subject->setAccount('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'account',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFlowReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getFlow()
        );
    }

    /**
     * @test
     */
    public function setFlowForStringSetsFlow()
    {
        $this->subject->setFlow('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'flow',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStructureReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getStructure()
        );
    }

    /**
     * @test
     */
    public function setStructureForStringSetsStructure()
    {
        $this->subject->setStructure('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'structure',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAddressReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getAddress()
        );
    }

    /**
     * @test
     */
    public function setAddressForStringSetsAddress()
    {
        $this->subject->setAddress('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'address',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStatusReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getStatus()
        );
    }

    /**
     * @test
     */
    public function setStatusForStringSetsStatus()
    {
        $this->subject->setStatus('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'status',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getResultReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getResult()
        );
    }

    /**
     * @test
     */
    public function setResultForStringSetsResult()
    {
        $this->subject->setResult('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'result',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getErrorReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getError()
        );
    }

    /**
     * @test
     */
    public function setErrorForIntegerSetsError()
    {
        $this->subject->setError(12);

        $this->assertAttributeEquals(
            12,
            'error',
            $this->subject
        );
    }
}
