<?php
namespace PPK\BmfBudgetImportXml\Controller\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\ImportBudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Target;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ImportController extends ActionController
{
    /**
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * @var \PPK\BmfBudgetImportXml\Service\File
     * @inject
     */
    protected $fileService;

    /**
     * @var \PPK\BmfBudgetImportXml\Service\Xml
     * @inject
     */
    protected $xmlService;

    /**
     * @var \PPK\BmfBudgetImportXml\Service\Budget\ImportService
     * @inject
     */
    protected $budgetImportService;

    /**
     * Initial action
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\ImportBudgetDto $importBudgetDto
     * @param array $result
     * @return void
     * @ignorevalidation $importBudgetDto
     */
    public function indexAction(
        ImportBudgetDto $importBudgetDto = null,
        array $result = []
    ) {
        $this->view->assign('dto', $importBudgetDto);
        $this->view->assign('result', $result);

        if ($importBudgetDto !== null) {
            // TODO Check why import budget is not existing here... maybe it exists... but not in view?
            if ($importBudgetDto->getFile()) {
                $target = new Target($importBudgetDto->getFile());
                $this->view->assign('sections', $target->getSections());
                $this->view->assign('section', $importBudgetDto->getSection());
                $this->view->assign('section_select', $target->getSections()[$importBudgetDto->getSection()]);
            }
        }
        $this->view->assign('budgets', $this->budgetRepository->findAll());
    }

    /**
     * Set budget entity
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto $budgetDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function budgetAction(BudgetDto $budgetDto)
    {
        $dto = new ImportBudgetDto();
        $dto->setStep(2);
        $dto->setBudget($budgetDto->getBudget());
        $this->forward('index', null, null, ['importBudgetDto' => $dto]);
    }

    /**
     * Upload excel file
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto $uploadDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function uploadAction(UploadDto $uploadDto)
    {
        if (!$file = $this->fileService->prepareUploadedFile($uploadDto->getFile())) {
            $this->addFlashMessage(
                $this->fileService->error['events'][0]['message'],
                'Upload File',
                AbstractMessage::ERROR
            );
            return;
        }

        $dto = new ImportBudgetDto();
        $dto->setStep(3);
        $dto->setBudget($uploadDto->getBudget());
        $dto->setFile($file);
        $dto->setSession(md5(time()));
        $this->forward('index', null, null, ['importBudgetDto' => $dto]);
    }

    /**
     * Process
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\ImportBudgetDto $importBudgetDto
     * @return void
     * @throws \PPK\BmfBudgetImportXml\Exception\BudgetImportException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction(ImportBudgetDto $importBudgetDto)
    {
        if (is_file(PATH_site . 'typo3temp/bmfprocess-' . $importBudgetDto->getSession() . '.js')) {
            // Preventing that actions are called magical, multiple times
            $this->view->assign('inProgress', true);

            $importBudgetDto->setStep(3);
            $this->forward('index', null, null, ['importBudgetDto' => $importBudgetDto]);
        }

        $importBudgetDto->setStep(4);
        $this->forward(
            'index',
            null,
            null,
            [
                'importBudgetDto' => $importBudgetDto,
                'result' => $this->budgetImportService->process($importBudgetDto)
            ]
        );
    }
}
