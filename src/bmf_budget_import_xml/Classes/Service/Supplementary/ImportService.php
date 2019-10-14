<?php
namespace PPK\BmfBudgetImportXml\Service\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\ImportSupplementaryDto;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Supplementary;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Supplementary\Title;

class ImportService extends \PPK\BmfBudgetImportXml\Service\Budget\ImportService
{
    /**
     * Original TitleRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitleRepository
     * @inject
     */
    protected $titleRepository;

    /**
     * Supplementary title repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SupplementaryTitleRepository
     * @inject
     */
    protected $supplementaryTitleRepository;

    /**
     * Supplementary budget repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SupplementaryBudgetRepository
     * @inject
     */
    protected $supplementaryBudgetRepository;

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
     * @param ImportSupplementaryDto $importSupplementaryDto
     * @return null
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function process(ImportSupplementaryDto $importSupplementaryDto)
    {
        // initialize model
        $supplementary = new Supplementary($importSupplementaryDto->getFile());

        // get titles
        $titleList = $supplementary->getTitles('');

        // start log
        $this->logService->initialize($importSupplementaryDto->getSession());

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

            if (!$persistedTitle = $this->titleRepository->findByBudgetAndAddress(
                $importSupplementaryDto->getBudget(),
                $title->getAddress()
            )) {
                // title not found ... create
                $persistedTitle = $this->createTitle($importSupplementaryDto->getBudget(), $title, $info);
            }

            // check if supplementary title already exist
            if ($supplementaryTitle = $persistedTitle->getSupplementaryBySupplementaryBudget(
                $importSupplementaryDto->getSupplementaryBudget()
            )) {
                $this->updateSupplementaryTitle(
                    $title->getFlow(),
                    $supplementaryTitle,
                    (int)$title->getTarget(),
                    (int)$title->getPageSupplementary()
                );
            } else {
                $this->addSupplementaryTitle(
                    $title->getFlow(),
                    $persistedTitle,
                    $importSupplementaryDto,
                    (int)$title->getTarget(),
                    (int)$title->getPageSupplementary()
                );
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

    /**
     * @param string $flow
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle|null $supplementaryTitle
     * @param int $value
     * @param int $page
     * @return bool
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    protected function updateSupplementaryTitle(
        $flow = '',
        \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryTitle = null,
        $value = 0,
        $page = 0
    ) {
        if ($flow === 'income') {
            $supplementaryTitle->setTargetIncome((int)$value);
            $supplementaryTitle->setTargetExpenses(null);
            $supplementaryTitle->setTargetPage((int)$page);
        }
        if ($flow === 'expenses') {
            $supplementaryTitle->setTargetIncome(null);
            $supplementaryTitle->setTargetExpenses((int)$value);
            $supplementaryTitle->setTargetPage((int)$page);
        }
        $this->supplementaryTitleRepository->update($supplementaryTitle);
        return true;
    }

    /**
     * @param string $flow
     * @param \PPKOELN\BmfBudget\Domain\Model\Title|null $title
     * @param ImportSupplementaryDto $importSupplementaryDto
     * @param int $value
     * @param int $page
     * @return bool
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    protected function addSupplementaryTitle(
        $flow = '',
        \PPKOELN\BmfBudget\Domain\Model\Title $title = null,
        ImportSupplementaryDto $importSupplementaryDto = null,
        $value = 0,
        $page = 0
    ) {
        $supplementaryTitle = new \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle();

        if ($flow === 'income') {
            $supplementaryTitle->setPid($importSupplementaryDto->getBudget()->getPidSupplementaryTitle());
            $supplementaryTitle->setTargetIncome($value);
            $supplementaryTitle->setTargetExpenses(null);
            $supplementaryTitle->setTargetPage($page);
        }
        if ($flow === 'expenses') {
            $supplementaryTitle->setPid($importSupplementaryDto->getBudget()->getPidSupplementaryTitle());
            $supplementaryTitle->setTargetIncome(null);
            $supplementaryTitle->setTargetExpenses($value);
            $supplementaryTitle->setTargetPage($page);
        }

        $title->addSupplementary($supplementaryTitle);
        $this->titleRepository->update($title);

        $supplementaryBudget = $importSupplementaryDto->getSupplementaryBudget();
        $supplementaryBudget->addSupplementaryTitle($supplementaryTitle);
        $this->supplementaryBudgetRepository->update($supplementaryBudget);

        return true;
    }
}
