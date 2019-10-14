<?php
namespace PPK\BmfBudgetImportXml\Controller\Label;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Label\UpdateLabelDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\UploadDto;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpdateController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * @var \PPK\BmfBudgetImportXml\Service\Label\UpdateService
     * @inject
     */
    protected $labelUpdateService;

    /**
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Label\UpdateLabelDto $updateLabelDto
     * @param array $result
     * @return null
     * @ignorevalidation $updateLabelDto
     */
    public function indexAction(UpdateLabelDto $updateLabelDto = null, array $result = [])
    {
        if (!$updateLabelDto) {
            $updateLabelDto = GeneralUtility::makeInstance(UpdateLabelDto::class);
        }
        $this->view->assign('dto', $updateLabelDto);
        $this->view->assign('result', $result);
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
        $dto = new UpdateLabelDto();
        $dto->setStep(2);
        $dto->setBudget($budgetDto->getBudget());
        $this->forward('index', null, null, ['updateLabelDto' => $dto]);
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
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            return;
        }

        $dto = new UpdateLabelDto();
        $dto->setStep(3);
        $dto->setBudget($uploadDto->getBudget());
        $dto->setFile($file);
        $dto->setSession(md5(time()));
        $this->forward('index', null, null, ['updateLabelDto' => $dto]);
    }

    /**
     * Process
     *
     * @param UpdateLabelDto $updateLabelDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction(UpdateLabelDto $updateLabelDto)
    {
        if (is_file(PATH_site . 'typo3temp/bmfprocess-' . $updateLabelDto->getSession() . '.js')) {
            // Preventing that actions are called magical, multiple times
            $this->view->assign('inProgress', true);

            $updateLabelDto->setStep(3);
            $this->forward(
                'index',
                null,
                null,
                [
                    'updateLabelDto' => $updateLabelDto
                ]
            );
        }

        $updateLabelDto->setStep(4);
        $this->forward(
            'index',
            null,
            null,
            [
                'updateLabelDto' => $updateLabelDto,
                'result' => $this->labelUpdateService->process($updateLabelDto)
            ]
        );
    }
}
