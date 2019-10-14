<?php
namespace PPK\BmfBudgetImportXml\Controller\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\UpdateBudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Actual;

class UpdateController extends ActionController
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
     * @var \PPK\BmfBudgetImportXml\Service\Budget\UpdateService
     * @inject
     */
    protected $budgetUpdateService;

    /**
     * Initial action
     *
     * @param UpdateBudgetDto $updateBudgetDto
     * @param array $result
     * @return void
     * @ignorevalidation $updateBudgetDto
     */
    public function indexAction(UpdateBudgetDto $updateBudgetDto = null, array $result = [])
    {
        $this->view->assign('dto', $updateBudgetDto);
        $this->view->assign('result', $result);

        if ($updateBudgetDto !== null) {
            if ($updateBudgetDto->getFile()) {
                $actual = new Actual($updateBudgetDto->getFile());
                $this->view->assign('sections', $actual->getSections());
                $this->view->assign('section', $updateBudgetDto->getSection());
                $this->view->assign('section_select', $actual->getSections()[$updateBudgetDto->getSection()]);
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
        $dto = new UpdateBudgetDto();
        $dto->setStep(2);
        $dto->setBudget($budgetDto->getBudget());
        $this->forward('index', null, null, ['updateBudgetDto' => $dto]);
    }

    /**
     * Upload excel file
     *
     * @param UploadDto $uploadDto
     * @return null
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function uploadAction(UploadDto $uploadDto)
    {
        if (!$file = $this->fileService->prepareUploadedFile($uploadDto->getFile())) {
            $this->addFlashMessage(
                $this->fileService->error['events'][0]['message'],
                'Upload File',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            return;
        }

        $dto = new UpdateBudgetDto();
        $dto->setStep(3);
        $dto->setBudget($uploadDto->getBudget());
        $dto->setFile($file);
        $dto->setSession(md5(time()));
        $this->forward('index', null, null, ['updateBudgetDto' => $dto]);
    }

    /**
     * Process
     *
     * @param UpdateBudgetDto $updateBudgetDto
     * @return null
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @TODO This code is pretty redundant (at least 3x existing)
     */
    public function processAction(UpdateBudgetDto $updateBudgetDto)
    {
        if (is_file(PATH_site . 'typo3temp/bmfprocess-' . $updateBudgetDto->getSession() . '.js')) {
            // Preventing that actions are called magical, multiple times
            $this->view->assign('inProgress', true);

            $updateBudgetDto->setStep(3);
            $this->forward('index', null, null, ['updateBudgetDto' => $updateBudgetDto]);
        }

        $updateBudgetDto->setStep(4);
        $this->forward(
            'index',
            null,
            null,
            [
                'updateBudgetDto' => $updateBudgetDto,
                'result' => $this->budgetUpdateService->process($updateBudgetDto)
            ]
        );
    }
}
