<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class SupplementaryTitleTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getActualPageReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getActualPage()
        );
    }

    /**
     * @test
     */
    public function setActualPageForIntegerSetsActualPage()
    {
        $this->subject->setActualPage(12);

        $this->assertAttributeEquals(
            12,
            'actualPage',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTargetPageReturnsInitialValueForInteger()
    {
        $this->assertSame(
            0,
            $this->subject->getTargetPage()
        );
    }

    /**
     * @test
     */
    public function setTargetPageForIntegerSetsActualPage()
    {
        $this->subject->setTargetPage(12);

        $this->assertAttributeEquals(
            12,
            'targetPage',
            $this->subject
        );
    }
}
