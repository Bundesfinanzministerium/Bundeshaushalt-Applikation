<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

class TitleRepository extends Repository
{
    /**
     * TitleRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    /**
     * FunctioneRepository
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functioneRepository;

    /**
     * GroupeRepository
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupeRepository;

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $function
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $group
     * @param \PPKOELN\BmfBudget\Domain\Model\Titlegroup|null $titlegroup
     * @param \PPKOELN\BmfBudget\Domain\Model\Budgetgroup|null $budgetgroup
     * @param string $flow
     * @param string $address
     * @param bool $flexible
     * @param string $title
     * @param null $value
     * @param int $page
     * @param int $pageLink
     * @param string $account
     * @param array $info
     * @return \PPKOELN\BmfBudget\Domain\Model\Title
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function create(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget,
        \PPKOELN\BmfBudget\Domain\Model\Section $section,
        \PPKOELN\BmfBudget\Domain\Model\Functione $function,
        \PPKOELN\BmfBudget\Domain\Model\Groupe $group,
        \PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup = null,
        \PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup = null,
        $flow = '',
        $address = '',
        $flexible = false,
        $title = '- dummytitle -',
        $value = null,
        $page = 0,
        $pageLink = 0,
        $account = 'target',
        &$info = []
    ) {
        $entityTitle = new \PPKOELN\BmfBudget\Domain\Model\Title();
        $entityTitle->setPid($budget->getPidTitle());
        $entityTitle->setBudget($budget);
        $entityTitle->setSection($section);
        $entityTitle->setFunctione($function);
        $entityTitle->setGroupe($group);
        $entityTitle->setTitlegroup($titlegroup);
        $entityTitle->setBudgetgroup($budgetgroup);

        $entityTitle->setTitle($title);
        $entityTitle->setAddress($address);
        $entityTitle->setFlexible($flexible);

        if ($flow === 'expenses') {
            $entityTitle->setFlow('a');
        } elseif ($flow === 'income') {
            $entityTitle->setFlow('e');
        }

        if ($account === 'target') {
            $entityTitle->setTargetPage($page);
        } elseif ($account === 'actual') {
            $entityTitle->setActualPage($page);
            $entityTitle->setActualPageLink($pageLink);
        }
        $this->setValues($entityTitle, $account, $flow, $value);
        $this->titleRepository->add($entityTitle);
        $this->titleRepository->persistenceManager->persistAll();

        // log
        $info['records']['title']['new'][] = [
            'address' => $address,
            'label' => $title
        ];

        return $entityTitle;
    }

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Title $entityTitle
     * @param string $account
     * @param string $flow
     * @param float $value
     * @return null
     */
    protected function setValues(
        \PPKOELN\BmfBudget\Domain\Model\Title &$entityTitle,
        $account = '',
        $flow = '',
        $value = 0.0
    ) {
        switch ($account) {
            case 'target':
                $entityTitle->setTargetIncome($flow === 'income' ? $value : null);
                $entityTitle->setTargetExpenses($flow === 'expenses' ? $value : null);
                break;
            case 'actual':
                $entityTitle->setActualIncome($flow === 'income' ? $value : null);
                $entityTitle->setActualExpenses($flow === 'expenses' ? $value : null);
                break;
            default:
        }
        return null;
    }
}
