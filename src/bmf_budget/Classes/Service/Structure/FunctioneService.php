<?php
namespace PPKOELN\BmfBudget\Service\Structure;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class FunctioneService extends AbstractStructureService
{
    /**
     * Function Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functionRepository;

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
            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $firstLevelEntity */
            if (!($firstLevelEntity = $this->functionRepository->findByBudgetAndAddress($budget, $address))) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $firstLevelEntity
            ];

            $result['list'] = $this->parseStructureList($firstLevelEntity->getFunctions(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getFunctions(), $account, $flow, $arrAddress[0])
            ];
        } elseif (preg_match('/^[0-9]{2}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $firstLevelEntity */
            if (!($firstLevelEntity = $this->functionRepository->findByBudgetAndAddress($budget, $arrAddress[0]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $secondLevelEntity */
            if (!($secondLevelEntity =
                $this->functionRepository->findByEntityAndAddress($firstLevelEntity, $arrAddress[1]))
            ) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $secondLevelEntity
            ];

            $result['list'] = $this->parseStructureList($secondLevelEntity->getFunctions(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getFunctions(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getFunctions(), $account, $flow, $arrAddress[1])
            ];
        } elseif (preg_match('/^[0-9]{3}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $firstLevelEntity */
            if (!($firstLevelEntity = $this->functionRepository->findByBudgetAndAddress($budget, $arrAddress[0]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $secondLevelEntity */
            if (!($secondLevelEntity =
                $this->functionRepository->findByEntityAndAddress($firstLevelEntity, $arrAddress[1]))
            ) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $thirdLevelEntity */
            if (!($thirdLevelEntity =
                $this->functionRepository->findByEntityAndAddress($secondLevelEntity, $arrAddress[2]))
            ) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $thirdLevelEntity
            ];

            $result['list'] = $this->parseTitelList($thirdLevelEntity->getTitles(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getFunctions(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getFunctions(), $account, $flow, $arrAddress[1]),
                $this->parseStructureList($secondLevelEntity->getFunctions(), $account, $flow, $arrAddress[2])
            ];
        } elseif (preg_match('/^[0-9]{9}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Title $title */
            if (!($title = $this->titleRepository->findByBudgetAndAddress($budget, $arrAddress[3]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $thirdLevelEntity */
            if (!($thirdLevelEntity = $title->getFunctione())) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $secondLevelEntity */
            if (!($secondLevelEntity = $thirdLevelEntity->getFunctione())) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Functione $firstLevelEntity */
            if (!($firstLevelEntity = $secondLevelEntity->getFunctione())) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Title',
                'entity' => $title
            ];

            $result['related'] = [
                'section' => $title->getSection(),
                'group' => $title->getGroupe()
            ];

            $titleList = $this->parseTitelList($thirdLevelEntity->getTitles(), $account, $flow, $arrAddress[3]);

            $result['list'] = $titleList;
            $result['rootpath'] = [
                $this->parseStructureList($budget->getFunctions(), $account, $flow, $firstLevelEntity->getAddress()),
                $this->parseStructureList(
                    $firstLevelEntity->getFunctions(),
                    $account,
                    $flow,
                    $secondLevelEntity->getAddress()
                ),
                $this->parseStructureList(
                    $secondLevelEntity->getFunctions(),
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

            $result['list'] = $this->parseStructureList($budget->getFunctions(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getFunctions(), $account, $flow, $arrAddress[0])
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
