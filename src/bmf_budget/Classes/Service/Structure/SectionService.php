<?php
namespace PPKOELN\BmfBudget\Service\Structure;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class SectionService extends AbstractStructureService
{
    /**
     * Section Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SectionRepository
     * @inject
     */
    protected $sectionRepository;

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
     * @param string $address Corresponding address (e.g. 01, 0101, 010123201)
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
            substr($address, 0, 2),
            substr($address, 0, 4),
            substr($address, 0, 9)
        ];

        $result = [];

        $firstLevelEntity = null;
        $secondLevelEntity = null;

        if (preg_match('/^[0-9]{2}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $firstLevelEntity */
            if (!($firstLevelEntity = $this->sectionRepository->findByBudgetAndAddress($budget, $address))) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $firstLevelEntity
            ];

            $result['list'] = $this->parseStructureList($firstLevelEntity->getSections(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getSections(), $account, $flow, $arrAddress[0])
            ];
        } elseif (preg_match('/^[0-9]{4}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $firstLevelEntity */
            if (!($firstLevelEntity = $this->sectionRepository->findByBudgetAndAddress($budget, $arrAddress[0]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $secondLevelEntity */
            if (!($secondLevelEntity =
                $this->sectionRepository->findByEntityAndAddress($firstLevelEntity, $arrAddress[1]))
            ) {
                return false;
            }

            $result['detail'] = [
                'type' => 'Structure',
                'entity' => $secondLevelEntity
            ];

            $result['list'] = $this->parseTitelList($secondLevelEntity->getTitles(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getSections(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getSections(), $account, $flow, $arrAddress[1])
            ];
        } elseif (preg_match('/^[0-9]{9}$/', $address)) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Title $title */
            if (!($title = $this->titleRepository->findByBudgetAndAddress($budget, $arrAddress[2]))) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $secondLevelEntity */
            if (!($secondLevelEntity = $title->getSection())) {
                return false;
            }

            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $firstLevelEntity */
            if (!($firstLevelEntity = $secondLevelEntity->getSection())) {
                return false;
            }

            $titleList = $this->parseTitelList($secondLevelEntity->getTitles(), $account, $flow, $arrAddress[2]);

            $result['detail'] = [
                'type' => 'Title',
                'entity' => $title
            ];

            $result['related'] = [
                'function' => $title->getFunctione(),
                'group' => $title->getGroupe()
            ];

            $result['list'] = $titleList;
            $result['rootpath'] = [
                $this->parseStructureList($budget->getSections(), $account, $flow, $arrAddress[0]),
                $this->parseStructureList($firstLevelEntity->getSections(), $account, $flow, $arrAddress[1]),
                $titleList
            ];
        } else {
            $result['detail'] = [
                'type' => 'Root',
                'entity' => $budget
            ];

            $result['list'] = $this->parseStructureList($budget->getSections(), $account, $flow);
            $result['rootpath'] = [
                $this->parseStructureList($budget->getSections(), $account, $flow, $arrAddress[0])
            ];
        }

        if (isset($firstLevelEntity)) {
            $result['childs'][] = $firstLevelEntity;
        }

        if (isset($secondLevelEntity)) {
            $result['childs'][] = $secondLevelEntity;
        }

        return $result;
    }
}
