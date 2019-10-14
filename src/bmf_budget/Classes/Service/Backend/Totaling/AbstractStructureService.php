<?php
namespace PPKOELN\BmfBudget\Service\Backend\Totaling;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractStructureService
{
    /**
     * TitleRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    /**
     * BudgetRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * SectionRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SectionRepository
     * @inject
     */
    protected $sectionRepository;

    /**
     * GroupRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupRepository;

    /**
     * FunctionRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functionRepository;

    /**
     * Persistence manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * Initialize totals - array
     *
     * @return array
     */
    protected function initializeTotals()
    {

        return [
            'income' => [
                'target' => null,
                'actual' => null
            ],
            'expenses' => [
                'target' => null,
                'actual' => null
            ],
        ];
    }

    /**
     * Iterate recursive through entity
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage $storage Recordset of entities
     * @param \PPKOELN\BmfBudget\Domain\Repository\AbstractStructureRepository $entityRepository
     *        Repository depending on entity
     * @param string $entityGetter Gettermethod
     * @return array
     */
    protected function iterateEntities(
        \TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage $storage = null,
        \PPKOELN\BmfBudget\Domain\Repository\AbstractStructureRepository &$entityRepository = null,
        $entityGetter = ''
    ) {

        /**
         * Initialize totals
         */
        $totals = $this->initializeTotals();

        foreach ($storage as $entity) {
            $childs = $entity->$entityGetter();
            if (count($childs) > 0) {
                $sumChild = $this->iterateEntities($childs, $entityRepository, $entityGetter);
                $entityRepository->update(
                    $this->updateEntity($entity, $sumChild)
                );
                $totals = $this->cummulateTotals($totals, $sumChild);
            } else {
                $titles = $entity->getTitles();
                if (count($titles) > 0) {
                    $sumTitles = $this->cummulateTitels($titles);
                    $entityRepository->update(
                        $this->updateEntity($entity, $sumTitles)
                    );
                    $totals = $this->cummulateTotals($totals, $sumTitles);
                }
            }
        }

        return $totals;
    }

    /**
     * Cummulate all titles entities in a totals array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles Recordset of titles
     *
     * @return array
     */
    protected function cummulateTitels(
        \TYPO3\CMS\Extbase\Persistence\ObjectStorage $titles
    ) {

        /**
         * Initialize total array
         */
        $totals = $this->initializeTotals();

        /**
         * Iterate through titles
         */
        foreach ($titles as $title) {
            /**
             * Processing title
             *
             * @var \PPKOELN\BmfBudget\Domain\Model\Title $title
             */

            if ($title->getFlow() === 'e') {
                $target = $title->getCurrentTargetIncome();
                $actual = $title->getActualIncome();

                if ($target !== null) {
                    $totals['income']['target'] = (float) $totals['income']['target'] + $target;
                }

                if ($actual !== null) {
                    $totals['income']['actual'] = (float) $totals['income']['actual'] + $actual;
                }
            } elseif ($title->getFlow() === 'a') {
                $target = $title->getCurrentTargetExpenses();
                $actual = $title->getActualExpenses();

                if ($target !== null) {
                    $totals['expenses']['target'] = (float) $totals['expenses']['target'] + $target;
                }

                if ($actual !== null) {
                    $totals['expenses']['actual'] = (float) $totals['expenses']['actual'] + $actual;
                }
            }
        }

        return $totals;
    }

    /**
     * Cumulating arrays
     *
     * @param array $base Original values
     * @param array $add Additional values
     *
     * @return array
     */
    protected function cummulateTotals(
        array $base = [],
        array $add = []
    ) {

        if ($add['income']['target'] !== null) {
            $base['income']['target'] = (float) $base['income']['target'] + $add['income']['target'];
        }

        if ($add['income']['actual'] !== null) {
            $base['income']['actual'] = (float) $base['income']['actual'] + $add['income']['actual'];
        }

        if ($add['expenses']['target'] !== null) {
            $base['expenses']['target'] = (float) $base['expenses']['target'] + $add['expenses']['target'];
        }

        if ($add['expenses']['actual'] !== null) {
            $base['expenses']['actual'] = (float) $base['expenses']['actual'] + $add['expenses']['actual'];
        }

        return $base;
    }

    /**
     * Update entity
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\AbstractStructure $entity Corresponding entity
     * @param array $calculated Values to calculate
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\AbstractStructure
     */
    protected function updateEntity(
        \PPKOELN\BmfBudget\Domain\Model\AbstractStructure $entity,
        array $calculated = []
    ) {

        $entity->setActualIncome($calculated['income']['actual']);
        $entity->setTargetIncome($calculated['income']['target']);
        $entity->setActualExpenses($calculated['expenses']['actual']);
        $entity->setTargetExpenses($calculated['expenses']['target']);

        return $entity;
    }
}
