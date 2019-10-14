<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Timeline;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class TitleTimelineService
{
    /**
     * Title Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    public function get(\PPKOELN\BmfBudget\Domain\Model\Budget $budgets = null, $structure = '', $address = '')
    {
        $return = null;
        if ($entity = $this->titleRepository->findByBudgetAndAddress($budgets, $address)) {
            $return = $this->parseEntity($entity);
        } else {
            $return = [
                'label' => [
                    'target' => '',
                    'actual' => ''
                ],
                'income' => [
                    'target' => null,
                    'actual' => null
                ],
                'expenses' => [
                    'target' => null,
                    'actual' => null
                ]
            ];
        }

        return [
            $budgets->getYear() => $return
        ];
    }

    /**
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Title $entity
     * @return array
     */
    public function parseEntity(\PPKOELN\BmfBudget\Domain\Model\Title $entity = null)
    {
        return [
            'label' => [
                'target' => $entity->getTitle(),
                'actual' => $entity->getTitle()
            ],
            'income' => [
                'target' => $entity->getCurrentTargetIncome() * 1000,
                'actual' => $entity->getCurrentActualIncome()
            ],
            'expenses' => [
                'target' => $entity->getCurrentTargetExpenses() * 1000,
                'actual' => $entity->getCurrentActualExpenses()
            ]
        ];
    }
}
