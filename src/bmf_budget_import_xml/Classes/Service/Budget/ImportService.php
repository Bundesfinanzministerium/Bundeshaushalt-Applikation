<?php
namespace PPK\BmfBudgetImportXml\Service\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\ImportBudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Target;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Target\Title;
use PPK\BmfBudgetImportXml\Exception\BudgetImportException;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;

class ImportService extends AbstractService implements SingletonInterface
{
    /**
     * Service Repository Section
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\SectionRepository
     * @inject
     */
    protected $xmlSectionRepository;

    /**
     * Service Repository Function
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $xmlFunctioneRepository;

    /**
     * Service Repository Group
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $xmlGroupeRepository;

    /**
     * Service Repository Titlegroup
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\TitlegroupRepository
     * @inject
     */
    protected $xmlTitlegroupRepository;

    /**
     * Service Repository Titlegroup
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\BudgetgroupRepository
     * @inject
     */
    protected $xmlBudgetgroupRepository;

    /**
     * Service Repository TitleRepository
     *
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\TitleRepository
     * @inject
     */
    protected $xmlTitleRepository;

    /**
     * Original TitleRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    /**
     * Log Service
     *
     * @var \PPKOELN\BmfBudget\Service\Backend\LogService
     * @inject
     */
    protected $logService;

    /**
     * Persistance manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * Main process
     *
     * @param ImportBudgetDto $importBudgetDto
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function process(ImportBudgetDto $importBudgetDto)
    {
        // initialize model
        $target = new Target($importBudgetDto->getFile());

        // get selected section nr
        $sectionNumber = $target->getSections()[$importBudgetDto->getSection()];

        // get titles
        $titleList = $target->getTitles('', $sectionNumber, '');

        // start log
        $this->logService->initialize($importBudgetDto->getSession());

        $info = [
            'start' => time(),
            'length' => $titleList->length,
            'count' => 0,
            'errors' => [],
            'records' => []
        ];

        $count = 0;

        /** @var \DOMElement $titleElement */
        foreach ($titleList as $titleElement) {
            $this->logService->write(
                [
                'bmf_budget_import_xml.budget_import' => [
                    'count' => $titleList->length,
                    'current' => ++$count
                ]
                ]
            );

            // merging page offset into xml
            $titleElement->setAttribute('einzelplan_seite_offset', (int)$importBudgetDto->getPageOffset());

            // prepare model
            $title = new Title($titleElement);

            // chapter above 89 are marked as "anlagentitel" and should ignored
            if ((int) substr($title->getChapter()->getAddress(), 2, 2) > 89) {
                // log
                $info['records']['title']['ignored'][] = [
                    'address' => $title->getAddress(),
                    'label' => $title->getLabel()
                ];
                continue;
            }

            if ($persistedTitle = $this->titleRepository->findByBudgetAndAddress(
                $importBudgetDto->getBudget(),
                $title->getAddress()
            )) {
                // title found ... update
                $this->updateTitle($persistedTitle, $title, $info);
            } else {
                // title not found ... create
                $this->createTitle($importBudgetDto->getBudget(), $title, $info);
            }
        }

        $this->logService->close(
            [
            'bmf_budget_import_xml.budget_update' => [
                'count' => $titleList->length,
                'current' => $count
            ]
            ]
        );

        $info['process'] = time() - $info['start'];

        return $info;
    }

    protected function updateTitle(
        \PPKOELN\BmfBudget\Domain\Model\Title $persistedTitle = null,
        \PPK\BmfBudgetImportXml\Domain\Model\Xml\Target\Title $title = null,
        &$info = []
    ) {

        // check label value
        if ($persistedTitle->getTitle() !== $title->getLabel()) {
            // labels are different
            $info['errors'][] = [
                'typ' => 'title',
                'address' => $title->getAddress(),
                'message' => 'Label differ',
                'difference' => [
                    'org' => $persistedTitle->getTitle(),
                    'new' => $title->getLabel()
                ]
            ];
        }

        // check target value
        if ($persistedTitle->getCurrentRevenue('target', $title->getFlow()) !== $title->getTarget()) {
            // target values are different
            $info['errors'][] = [
                'typ' => 'title',
                'address' => $title->getAddress(),
                'message' => 'Target value differ',
                'difference' => [
                    'org' => $persistedTitle->getCurrentRevenue('target', $title->getFlow()),
                    'new' => $title->getTarget()
                ]
            ];
        }

        // update value
        if ($title->getFlow() === 'income') {
            $persistedTitle->setTargetIncome($title->getTarget());
        } elseif ($title->getFlow() === 'expenses') {
            $persistedTitle->setTargetExpenses($title->getTarget());
        } else {
            $info['errors'][] = [
                'typ' => 'title',
                'address' => $title->getAddress(),
                'message' => 'Flow "' . $title->getFlow() . '" not assignable'
            ];
        }

        // Workaround for updating sections
        if ($persistedTitle->getSection()->getTitle() == '- dummytitle -' &&
            $title->getChapter()->getLabel() != '- dummytitle -'
        ) {
            $this->xmlSectionRepository->create(
                $persistedTitle->getBudget(),
                $title->getChapter()->getAddress(),
                $title->getChapter()->getLabel()
            );
        }

        // update page
        $persistedTitle->setActualPage($title->getPage());
        // update page link (page - offset)
        $persistedTitle->setActualPageLink($title->getPageLink());
        // update title
        $this->titleRepository->update($persistedTitle);
        // persist title
        $this->persistenceManager->persistAll();
        // log
        $info['records']['title']['updated'][] = [
            'address' => $title->getAddress(),
            'label' => $title->getLabel()
        ];
    }

    protected function createTitle(Budget $budget, Title $title = null, &$info = [])
    {
        // create section
        $section = $this->xmlSectionRepository->create(
            $budget,
            $title->getSection()->getAddress(),
            $title->getSection()->getLabel(),
            $info
        );

        // create chapter
        $chapter = $this->xmlSectionRepository->create(
            $budget,
            $title->getChapter()->getAddress(),
            $title->getChapter()->getLabel(),
            $info
        );

        // create function
        $function = $this->xmlFunctioneRepository->create(
            $budget,
            $title->getFunktion(),
            '- dummytitle -',
            $info
        );

        // create group
        $group = $this->xmlGroupeRepository->create(
            $budget,
            $title->getGroup(),
            '- dummytitle -',
            $info
        );

        // create titlegroup
        if ($title->getTitlegroup() !== null) {
            $titleGroup = $this->xmlTitlegroupRepository->create(
                $budget,
                $chapter,
                $title->getTitlegroup()->getAddress(),
                $title->getTitlegroup()->getLabel(),
                $info
            );
        } else {
            $titleGroup = null;
        }

        // create budgetgroup
        if ($title->getBudgetgroup() !== null) {
            $budgetGroup = $this->xmlBudgetgroupRepository->create(
                $budget,
                $chapter,
                $title->getBudgetgroup()->getLabel(),
                $info
            );
        } else {
            $budgetGroup = null;
        }

        // create title
        return $this->xmlTitleRepository->create(
            $budget,
            $chapter,
            $function,
            $group,
            $titleGroup,
            $budgetGroup,
            $title->getFlow(),
            $title->getAddress(),
            $title->getFlexible(),
            $title->getLabel(),
            $title->getTarget(),
            $title->getPage(),
            $title->getPageLink(),
            'target',
            $info
        );
    }
}
