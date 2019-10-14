<?php
namespace PPKOELN\BmfBudget\Service\Structure;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class GroupeService extends AbstractStructureService
{

    /**
     * Group Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupRepository;

    /**
     * Title Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    /**
     * Get current and child entities of corresponding informations
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget Corresponding budget entity
     * @param string $account Corresponding account information ('target' or 'actual')
     * @param string $flow Corresponding flow information ('income' or 'expenses')
     * @param string $address Corresponding address (e.g. 2, 23, 232, 010123201)
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function get(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget = null,
        $account = '',
        $flow = '',
        $address = ''
    ) {
        $arrAddress = [
            substr($address, 0, 1),
            substr($address, 0, 2),
            substr($address, 0, 3),
            substr($address, 0, 9)
        ];

        $result = [];

        $firstLevelEntity = null;
        $secondLevelEntity = null;
        $thirdLevelEntity = null;

        if (preg_match('/^[0-9]{1}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $firstLevelEntity */
            if (!($firstLevelEntity = $this->groupRepository->findByBudgetAndAddress($budget, $address))) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $firstLevelEntity
            ];

            $result['list'] = $this->parseStructureList($firstLevelEntity->getGroups(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getGroups(), $account, $flow, $arrAddress[0])
            ];
        } elseif (preg_match('/^[0-9]{2}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $firstLevelEntity */
            if (!($firstLevelEntity = $this->groupRepository->findByBudgetAndAddress($budget, $arrAddress[0]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $secondLevelEntity */
            if (!($secondLevelEntity = $this->groupRepository->findByEntityAndAddress(
                $firstLevelEntity,
                $arrAddress[1]
            ))
            ) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $secondLevelEntity
            ];

            $result['list'] = $this->parseStructureList($secondLevelEntity->getGroups(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getGroups(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getGroups(), $account, $flow, $arrAddress[1])
            ];
        } elseif (preg_match('/^[0-9]{3}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $firstLevelEntity */
            if (!($firstLevelEntity = $this->groupRepository->findByBudgetAndAddress($budget, $arrAddress[0]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $secondLevelEntity */
            if (!($secondLevelEntity =
                $this->groupRepository->findByEntityAndAddress($firstLevelEntity, $arrAddress[1]))
            ) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $thirdLevelEntity */
            if (!($thirdLevelEntity =
                $this->groupRepository->findByEntityAndAddress($secondLevelEntity, $arrAddress[2]))
            ) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $thirdLevelEntity
            ];

            $result['list'] = $this->parseTitelList($thirdLevelEntity->getTitles(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getGroups(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getGroups(), $account, $flow, $arrAddress[1]),
                $this->parseStructureList($secondLevelEntity->getGroups(), $account, $flow, $arrAddress[2])
            ];
        } elseif (preg_match('/^[0-9]{9}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Title $title */
            if (!($title = $this->titleRepository->findByBudgetAndAddress($budget, $arrAddress[3]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $thirdLevelEntity */
            if (!($thirdLevelEntity = $title->getGroupe())) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $secondLevelEntity */
            if (!($secondLevelEntity = $thirdLevelEntity->getGroupe())) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Groupe $firstLevelEntity */
            if (!($firstLevelEntity = $secondLevelEntity->getGroupe())) {
                return false;
            }

            $titleList = $this->parseTitelList($thirdLevelEntity->getTitles(), $account, $flow, $arrAddress[3]);

            $result['detail'] = [
                'type' => 'Title',
                'entity' => $title
            ];

            $result['related'] = [
                'section' => $title->getSection(),
                'function' => $title->getFunctione()
            ];

            $result['list'] = $titleList;
            $result['rootpath'] = [
                $this->parseStructureList($budget->getGroups(), $account, $flow, $firstLevelEntity->getAddress()),
                $this->parseStructureList(
                    $firstLevelEntity->getGroups(),
                    $account,
                    $flow,
                    $secondLevelEntity->getAddress()
                ),
                $this->parseStructureList(
                    $secondLevelEntity->getGroups(),
                    $account,
                    $flow,
                    $thirdLevelEntity->getAddress()
                ),
                $titleList
            ];
        } else {
            $result['detail'] = [
                'type' => 'Root',
                'entity' => $budget
            ];

            $result['list'] = $this->parseStructureList($budget->getGroups(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getGroups(), $account, $flow, $arrAddress[0])
            ];
        }

        if (isset($firstLevelEntity)) {
            $result['childs'][] = $firstLevelEntity;
        }

        if (isset($secondLevelEntity)) {
            $result['childs'][] = $secondLevelEntity;
        }

        if (isset($thirdLevelEntity)) {
            $result['childs'][] = $thirdLevelEntity;
        }

        return $result;
    }
}
