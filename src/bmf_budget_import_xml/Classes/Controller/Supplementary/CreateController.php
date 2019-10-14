<?php
namespace PPK\BmfBudgetImportXml\Controller\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\BudgetDto;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\CreateSupplementaryDto;

class CreateController extends ActionController
{
    /**
     * Budget Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * Supplementary Create Service
     *
     * @var \PPK\BmfBudgetImportXml\Service\Supplementary\CreateService
     * @inject
     */
    protected $supplementaryCreateService;

    /**
     * Initial action
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\CreateSupplementaryDto $createSupplementaryDto
     * @param array $result
     *
     * @return null
     */
    public function indexAction(CreateSupplementaryDto $createSupplementaryDto = null, array $result = [])
    {
        $this->view->assign('dto', $createSupplementaryDto);
        $this->view->assign('result', $result);
        $this->view->assign('budgets', $this->budgetRepository->findAll());
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
        $dto = new CreateSupplementaryDto();
        $dto->setStep(2);
        $dto->setBudget($budgetDto->getBudget());

        $this->forward(
            'index',
            null,
            null,
            [
                'createSupplementaryDto' => $dto,
            ]
        );
    }

    /**
     * Process
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\CreateSupplementaryDto $createSupplementaryDto
     * @throws \PPK\BmfBudgetImportXml\Exception\BudgetImportException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction(CreateSupplementaryDto $createSupplementaryDto)
    {
        $createSupplementaryDto->setStep(3);
        $this->forward(
            'index',
            null,
            null,
            [
                'createSupplementaryDto' => $createSupplementaryDto,
                'result' => $this->supplementaryCreateService->process($createSupplementaryDto),
            ]
        );
    }
}
