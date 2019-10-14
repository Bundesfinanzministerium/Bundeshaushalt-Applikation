<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Timeline;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \PPKOELN\BmfBudget\Domain\Model\AbstractValue;
use \PPKOELN\BmfBudget\Domain\Model\Budget;

class StructureTimelineService
{
    /**
     * Section Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SectionRepository
     * @inject
     */
    protected $sectionRepository;

    /**
     * Function Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functioneRepository;

    /**
     * Group Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupeRepository;

    /**
     * @param Budget $budgets
     * @param string $structure
     * @param string $address
     * @return null
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function get(Budget $budgets = null, $structure = '', $address = '')
    {
        $entityRepository = null;
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

        switch (strtolower($structure)) {
            case 'section':
                $entityRepository = $this->sectionRepository;
                break;
            case 'function':
                $entityRepository = $this->functioneRepository;
                break;
            case 'group':
                $entityRepository = $this->groupeRepository;
                break;
            default:
        }

        if ($entity = $entityRepository->findByBudgetAndAddress($budgets, $address)) {
            $return = $this->parseEntity($entity);
        }

        return [
            $budgets->getYear() => $return
        ];
    }

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\AbstractValue $entity
     * @return array
     */
    public function parseEntity(AbstractValue $entity = null)
    {
        $retval = [
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

        if ($entity !== null) {
            $retval = [
                'label' => [
                    'target' => $entity->getTitle(),
                    'actual' => $entity->getTitle()
                ],
                'income' => [
                    'target' => $entity->getTargetIncome() * 1000,
                    'actual' => $entity->getActualIncome()
                ],
                'expenses' => [
                    'target' => $entity->getTargetExpenses() * 1000,
                    'actual' => $entity->getActualExpenses()
                ]
            ];
        }
        return $retval;
    }
}
