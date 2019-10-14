<?php
namespace PPK\BmfBudgetImportXml\Service\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual\Title;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\UpdateBudgetDto;

class UpdateService extends AbstractService implements SingletonInterface
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
     * @param UpdateBudgetDto $updateBudgetDto
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function process(UpdateBudgetDto $updateBudgetDto)
    {
        // initialize model
        $actual = new Actual($updateBudgetDto->getFile());

        // get selected section nr
        $sectionNumber = $actual->getSections()[$updateBudgetDto->getSection()];

        // get titles
        $titleList = $actual->getTitles('', $sectionNumber, '');

        // start log
        $this->logService->initialize($updateBudgetDto->getSession());

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
                    'bmf_budget_import_xml.budget_update' => [
                        'count' => $titleList->length,
                        'current' => ++$count
                    ]
                ]
            );

            // merging page offset into xml
            $titleElement->setAttribute('einzelplan_seite_offset', (int)$updateBudgetDto->getPageOffset());

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
                $updateBudgetDto->getBudget(),
                $title->getAddress()
            )
            ) {
                // title found ... update
                $this->updateTitle($persistedTitle, $title, $info);
            } else {
                // title not found ... create
                $this->createTitle($updateBudgetDto->getBudget(), $title, $info);
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
        \PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual\Title $title = null,
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
            $persistedTitle->setActualIncome($title->getActual());
        } elseif ($title->getFlow() === 'expenses') {
            $persistedTitle->setActualExpenses($title->getActual());
        } else {
            $info['errors'][] = [
                'typ' => 'title',
                'address' => $title->getAddress(),
                'message' => 'Flow "' . $title->getFlow() . '" not assignable'
            ];
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

    protected function createTitle(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget,
        \PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual\Title $title = null,
        &$info = []
    ) {
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
            '- dummytitle -',
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
        $this->xmlTitleRepository->create(
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
            $title->getActual(),
            $title->getPage(),
            $title->getPageLink(),
            'actual',
            $info
        );
    }
}
