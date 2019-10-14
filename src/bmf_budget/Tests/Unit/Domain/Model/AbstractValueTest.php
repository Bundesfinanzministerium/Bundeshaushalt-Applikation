<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractValueTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\AbstractValue
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\AbstractValue();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getActualIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getActualIncome()
        );
    }

    /**
     * @test
     */
    public function setActualIncomeForFloatSetsActualIncome()
    {
        $this->subject->setActualIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'actualIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getActualExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getActualExpenses()
        );
    }

    /**
     * @test
     */
    public function setActualExpensesForFloatSetsActualExpenses()
    {
        $this->subject->setActualExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'actualExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getTargetIncomeReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getTargetIncome()
        );
    }

    /**
     * @test
     */
    public function setTargetIncomeForFloatSetsTargetIncome()
    {
        $this->subject->setTargetIncome(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'targetIncome',
            $this->subject,
            '',
            0.000000001
        );
    }

    /**
     * @test
     */
    public function getTargetExpensesReturnsInitialValueForFloat()
    {
        $this->assertSame(
            null,
            $this->subject->getTargetExpenses()
        );
    }

    /**
     * @test
     */
    public function setTargetExpensesForFloatSetsTargetExpenses()
    {
        $this->subject->setTargetExpenses(3.14159265);

        $this->assertAttributeEquals(
            3.14159265,
            'targetExpenses',
            $this->subject,
            '',
            0.000000001
        );
    }
}
