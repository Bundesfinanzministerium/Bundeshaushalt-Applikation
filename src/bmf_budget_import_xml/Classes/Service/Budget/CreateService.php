<?php
namespace PPK\BmfBudgetImportXml\Service\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\CreateBudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Page;
use PPK\BmfBudgetImportXml\Exception\BudgetImportException;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CreateService extends AbstractService implements SingletonInterface
{
    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * Page Repository
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\PageRepository
     * @inject
     */
    protected $pagesRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\CreateBudgetDto $createBudgetDto
     * @return array
     * @throws BudgetImportException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function process(CreateBudgetDto $createBudgetDto)
    {
        $info = [
            'start' => time(),
            'record' => null
        ];

        if ($this->pagesRepository->findByUid($createBudgetDto->getRootSysFolderPid()) === null) {
            throw new BudgetImportException('Rootpage not found', 1484044452426);
        }

        if ($this->pagesRepository->findByTitleAndPid(
            $createBudgetDto->getYear(),
            $createBudgetDto->getRootSysFolderPid()
        )) {
            throw new BudgetImportException('Year already exists', 1484044503713);
        }

        if ($latest = $this->pagesRepository->findLastEntityByPid($createBudgetDto->getRootSysFolderPid())) {
            $sorting = $latest->getSorting() + 256;
        } else {
            $sorting = 0;
        }

        /** @var Page $pageYear */
        $pageYear = GeneralUtility::makeInstance(Page::class);
        $pageYear->setPid($createBudgetDto->getRootSysFolderPid());
        $pageYear->setSorting($sorting);
        $pageYear->setTitle($createBudgetDto->getYear());
        $pageYear->setDoktype(254);
        $this->persistenceManager->add($pageYear);
        $this->persistenceManager->persistAll();

        $title = $this->setupPage($pageYear->getUid(), 1 * 256, 'Titel');
        $section = $this->setupPage($pageYear->getUid(), 2 * 256, 'Einzelplan');
        $function = $this->setupPage($pageYear->getUid(), 3 * 256, 'Funktion');
        $group = $this->setupPage($pageYear->getUid(), 4 * 256, 'Gruppe');
        $budgetgroup = $this->setupPage($pageYear->getUid(), 5 * 256, 'Umsatzgruppe');
        $titlegroup = $this->setupPage($pageYear->getUid(), 6 * 256, 'Titelgruppe');
        $supplementary = $this->setupPage($pageYear->getUid(), 7 * 256, 'Nachtrag');
        $this->persistenceManager->persistAll();

        $pageYear->setTsConfig('TSFE.constants.bmf.budget.pids {
	title = ' . $title->getUid() . '
	section = ' . $section->getUid() . '
	function = ' . $function->getUid() . '
	group = ' . $group->getUid() . '
	budgetgroup = ' . $budgetgroup->getUid() . '
	titlegroup = ' . $titlegroup->getUid() . '
	supplementary = ' . $supplementary->getUid() . '
}
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bmf_budget/Configuration/TypoScript/TsConfig/pageTsConfig.ts">');
        $this->persistenceManager->update($pageYear);

        /** @var Budget $budget */
        $budget = GeneralUtility::makeInstance(Budget::class);
        $budget->setPid($pageYear->getUid());
        $budget->setTitleTarget($createBudgetDto->getTitle());
        $budget->setYear($createBudgetDto->getYear());
        $budget->setPidTitle($title->getUid());
        $budget->setPidSection($section->getUid());
        $budget->setPidFunction($function->getUid());
        $budget->setPidGroup($group->getUid());
        $budget->setPidBudgetgroup($budgetgroup->getUid());
        $budget->setPidTitlegroup($titlegroup->getUid());
        $budget->setPidSupplementaryBudget($supplementary->getUid());
        $budget->setPidSupplementaryTitle($supplementary->getUid());
        $this->budgetRepository->add($budget);
        $this->persistenceManager->persistAll();

        $info['process'] = time() - $info['start'];
        $info['record'] = $budget;

        return $info;
    }

    /**
     * @param int $pid
     * @param int $sorting
     * @param string $title
     * @return Page
     */
    protected function setupPage($pid, $sorting, $title)
    {
        /** @var Page $page */
        $page = GeneralUtility::makeInstance(Page::class);
        $page->setPid($pid);
        $page->setSorting($sorting);
        $page->setTitle($title);
        $page->setDoktype(254);
        $this->persistenceManager->add($page);
        return $page;
    }
}
