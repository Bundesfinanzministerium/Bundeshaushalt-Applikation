<?php
namespace PPKOELN\BmfBudget\Tests\Unit\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class GroupeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Model\Groupe
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getGroupsReturnsInitialValueForGroupe()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getGroups()
        );
    }

    /**
     * @test
     */
    public function setGroupsForObjectStorageContainingGroupeSetsGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $objectStorageHoldingExactlyOneGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneGroups->attach($group);
        $this->subject->setGroups($objectStorageHoldingExactlyOneGroups);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneGroups,
            'groups',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addGroupToObjectStorageHoldingGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $groupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['attach'], [], '', false);
        $groupsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($group));
        $this->inject($this->subject, 'groups', $groupsObjectStorageMock);

        $this->subject->addGroup($group);
    }

    /**
     * @test
     */
    public function removeGroupFromObjectStorageHoldingGroups()
    {
        $group = new \PPKOELN\BmfBudget\Domain\Model\Groupe();
        $groupsObjectStorageMock = $this->getMock(ObjectStorage::class, ['detach'], [], '', false);
        $groupsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($group));
        $this->inject($this->subject, 'groups', $groupsObjectStorageMock);

        $this->subject->removeGroup($group);
    }
}
