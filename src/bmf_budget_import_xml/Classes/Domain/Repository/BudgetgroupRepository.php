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
use PPKOELN\BmfBudget\Domain\Model\Section;
use PPKOELN\BmfBudget\Domain\Model\Budgetgroup;

class BudgetgroupRepository extends Repository
{
    /**
     * BudgetgroupRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetgroupRepository
     * @inject
     */
    protected $budgetgroupRepository;

    /**
     * Check if Budgetgroup already exist ... if not it will be created
     *
     * @param Budget $budget
     * @param Section $section
     * @param string $title
     * @param array $info
     * @return Budgetgroup
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function create(Budget $budget, Section $section, $title = '- dummytitle -', &$info = [])
    {
        if ($budgetgroup = $this->budgetgroupRepository->findBySectionAndTitle($section, $title)) {
            return $budgetgroup;
        }

        $budgetgroup = new Budgetgroup();
        $budgetgroup->setPid($budget->getPidBudgetgroup());
        $budgetgroup->setSection($section);
        $budgetgroup->setTitle($title);
        $this->budgetgroupRepository->add($budgetgroup);
        $this->budgetgroupRepository->persistenceManager->persistAll();
        $info['records']['budgetgroup']['new'][] = [
            'budget' => $budget->getYear(),
            'section' => $section->getAddress(),
            'label' => $title
        ];
        return $budgetgroup;
    }
}
