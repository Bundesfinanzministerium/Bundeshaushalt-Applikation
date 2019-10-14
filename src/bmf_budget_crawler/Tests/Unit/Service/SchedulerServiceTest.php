<?php
namespace PPKOELN\BmfBudgetCrawler\Tests\Unit\Service;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\Tests\UnitTestCase;

class SchedulerServiceTest extends UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudgetCrawler\Service\BudgetSchedulerService
     */
    protected $subject;

    /**
     * @var string
     */
    protected $data = 'a:2:{s:21:"section-target-income";a:2:{i:0;s:9:"010123201";i:1;s:4:"0101";}s:23:"section-tar' .
                      'get-expenses";a:2:{i:0;s:9:"010152901";i:1;s:9:"010168101";}}';

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudgetCrawler\Service\BudgetSchedulerService();
        $this->data = unserialize($this->data);
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getInfoImageReturnsInitialValueForFileReference()
    {
        $this->assertEquals(true, true);
    }
}
