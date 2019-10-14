<?php
namespace PPK\BmfBudgetImportXml\Controller\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\SupplementaryDto;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\ImportSupplementaryDto;

class ImportController extends ActionController
{
    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * File Service
     *
     * @var \PPK\BmfBudgetImportXml\Service\File
     * @inject
     */
    protected $fileService;

    /**
     * Supplementary Import Service
     *
     * @var \PPK\BmfBudgetImportXml\Service\Supplementary\ImportService
     * @inject
     */
    protected $supplementaryImportService;

    /**
     * Initial action
     *
     * @param ImportSupplementaryDto $importSupplementaryDto
     * @param array $result
     * @return null
     */
    public function indexAction(ImportSupplementaryDto $importSupplementaryDto = null, array $result = [])
    {
        $this->view->assign('dto', $importSupplementaryDto);
        $this->view->assign('result', $result);
        $this->view->assign('budgets', $this->budgetRepository->findAll());

        if ($importSupplementaryDto !== null) {
            $this->view->assign(
                'supplementaryBudgets',
                $importSupplementaryDto->getBudget()->getSupplementaryBudgets()
            );
        }
        return null;
    }

    /**
     * Set budget entity
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto $budgetDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function budgetAction(BudgetDto $budgetDto)
    {
        $dto = new ImportSupplementaryDto();
        $dto->setStep(2);
        $dto->setBudget($budgetDto->getBudget());
        $this->forward('index', null, null, ['importSupplementaryDto' => $dto]);
    }

    /**
     * Set supplementary entity
     *
     * @param SupplementaryDto $supplementaryDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function supplementaryAction(SupplementaryDto $supplementaryDto)
    {
        $dto = new ImportSupplementaryDto();
        $dto->setStep(3);
        $dto->setBudget($supplementaryDto->getBudget());
        $dto->setSupplementaryBudget($supplementaryDto->getSupplementary());
        $this->forward('index', null, null, ['importSupplementaryDto' => $dto]);
    }

    /**
     * Upload excel file
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\ImportSupplementaryDto $importSupplementaryDto
     * @return null|void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function uploadAction(ImportSupplementaryDto $importSupplementaryDto)
    {
        if (!$this->fileService->prepareUploadedFile($importSupplementaryDto->getFile())) {
            $this->addFlashMessage(
                $this->fileService->error['events'][0]['message'],
                'Upload File',
                AbstractMessage::ERROR
            );
            return null;
        }
        $importSupplementaryDto->setStep(4);
        $importSupplementaryDto->setSession(md5(time()));
        $this->forward('index', null, null, ['importSupplementaryDto' => $importSupplementaryDto]);
    }

    /**
     * Process
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\ImportSupplementaryDto $importSupplementaryDto
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction(ImportSupplementaryDto $importSupplementaryDto)
    {
        if (is_file(PATH_site.'typo3temp/bmfprocess-'.$importSupplementaryDto->getSession().'.js')) {
            /**
             * Preventing that actions are called magical, multiple times
             */
            $this->view->assign('inProgress', true);
            $importSupplementaryDto->setStep(5);
            $this->forward(
                'index',
                null,
                null,
                [
                    'importSupplementaryDto' => $importSupplementaryDto,
                ]
            );
        }

        $importSupplementaryDto->setStep(5);
        $this->forward(
            'index',
            null,
            null,
            [
                'importSupplementaryDto' => $importSupplementaryDto,
                'result' => $this->supplementaryImportService->process($importSupplementaryDto),
            ]
        );
    }
}
