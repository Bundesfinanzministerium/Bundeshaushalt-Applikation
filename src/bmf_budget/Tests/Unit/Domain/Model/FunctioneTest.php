<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class FunctioneTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Functione
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Functione();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getFunctionsReturnsInitialValueForFunction()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getFunctions()
        );
    }

    /**
     * @test
     */
    public function setFunctionsForObjectStorageContainingFunctionSetsFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $objectStorageHoldingExactlyOneFunctions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneFunctions->attach($function);
        $this->subject->setFunctions($objectStorageHoldingExactlyOneFunctions);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneFunctions,
            'functions',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addFunctionToObjectStorageHoldingFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $functionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $functionsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($function));
        $this->inject($this->subject, 'functions', $functionsObjectStorageMock);

        $this->subject->addFunction($function);
    }

    /**
     * @test
     */
    public function removeFunctionFromObjectStorageHoldingFunctions()
    {
        $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
        $functionsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $functionsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($function));
        $this->inject($this->subject, 'functions', $functionsObjectStorageMock);

        $this->subject->removeFunction($function);
    }
}
