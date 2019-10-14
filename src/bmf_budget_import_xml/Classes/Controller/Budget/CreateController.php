<?php
namespace PPK\BmfBudgetImportXml\Controller\Budget;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\CreateBudgetDto;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class CreateController extends ActionController
{
    /**
     * @var \PPK\BmfBudgetImportXml\Domain\Repository\PageRepository
     * @inject
     */
    protected $pagesRepository;

    /**
     * @var \PPK\BmfBudgetImportXml\Service\Budget\CreateService
     * @inject
     */
    protected $budgetCreateService;

    /**
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\CreateBudgetDto $createBudgetDto
     * @param array $result
     */
    public function indexAction(CreateBudgetDto $createBudgetDto = null, array $result = [])
    {
        $this->view->assign('dto', $createBudgetDto);
        $this->view->assign('result', $result);
    }

    /**
     * Process
     *
     * @param \PPK\BmfBudgetImportXml\Domain\Model\Dto\Budget\CreateBudgetDto $createBudgetDto
     * @throws \PPK\BmfBudgetImportXml\Exception\BudgetImportException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function processAction(CreateBudgetDto $createBudgetDto)
    {
        $this->forward(
            'index',
            null,
            null,
            [
                'createBudgetDto' => $createBudgetDto,
                'result' => $this->budgetCreateService->process($createBudgetDto)
            ]
        );
    }
}
