<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use PPKOELN\BmfBudget\Domain\Model\Groupe;

class GroupeRepository extends Repository
{
    /**
     * GroupeRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupeRepository;

    /**
     * @param Budget $budget
     * @param string $address
     * @param string $title
     * @param array $info
     * @return bool|object|Groupe
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function create(Budget $budget, $address = '', $title = '- dummytitle -', &$info = [])
    {
        if (strlen($address) === 1) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($group = $this->groupeRepository->findByBudgetAndAddress($budget, $address)) {
                if ($title !== '- dummytitle -' && $title !== $group->getTitle()) {
                    $group->setTitle($title);
                    $this->groupeRepository->update($group);
                    $this->groupeRepository->persistenceManager->persistAll();
                    $info['records']['group']['updated'][] = [
                        'budget' => $budget->getYear(),
                        'address' => $address,
                        'label' => $title
                    ];
                }
                return $group;
            }
            $group = new Groupe();
            $group->setPid($budget->getPidGroup());
            $group->setBudget($budget);
            $group->setAddress($address);
            $group->setTitle($title);
            $this->groupeRepository->add($group);
            $this->groupeRepository->persistenceManager->persistAll();
            $info['records']['group']['new'][] = [
                'budget' => $budget->getYear(),
                'address' => $address,
                'label' => $title
            ];
            return $group;
        } elseif (strlen($address) > 1) {
            // sub level
            $parent = $this->create($budget, substr($address, 0, strlen($address) - 1), '- dummytitle -', $info);
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($group = $this->groupeRepository->findByEntityAndAddress($parent, $address)) {
                if ($title !== '- dummytitle -' && $title !== $section->getTitle()) {
                    $group->setTitle($title);
                    $this->groupeRepository->update($group);
                    $this->groupeRepository->persistenceManager->persistAll();
                    $info['records']['group']['updated'][] = [
                        'budget' => $budget->getYear(),
                        'address' => $address,
                        'label' => $title
                    ];
                }
                return $group;
            }
            $group = new Groupe();
            $group->setPid($budget->getPidGroup());
            $group->setGroupe($parent);
            $group->setAddress($address);
            $group->setTitle($title);
            $this->groupeRepository->add($group);
            $this->groupeRepository->persistenceManager->persistAll();
            $info['records']['group']['new'][] = [
                'budget' => $budget->getYear(),
                'address' => $address,
                'label' => $title
            ];
            return $group;
        }
        return false;
    }
}
