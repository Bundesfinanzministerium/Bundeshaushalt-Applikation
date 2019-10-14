<?php
namespace PPK\BmfBudgetImportXml\Service\Supplementary;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Exception\BudgetImportException;
use PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Supplementary\CreateSupplementaryDto;

class CreateService extends AbstractService implements SingletonInterface
{
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
     * Main process
     *
     * @param CreateSupplementaryDto $createSupplementaryDto
     * @return null
     * @throws BudgetImportException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function process(CreateSupplementaryDto $createSupplementaryDto)
    {
        $info = [
            'start' => time(),
            'record' => null
        ];

        if ($this->pagesRepository->findByUid($createSupplementaryDto->getRootSysFolderPid()) === null) {
            throw new BudgetImportException('Rootpage not found', 1484044452426);
        }

        if ($latest = $this->pagesRepository->findLastEntityByPid($createSupplementaryDto->getRootSysFolderPid())) {
            $sorting = $latest->getSorting() + 256;
        } else {
            $sorting = 0;
        }

        $supplementaryBudget = new SupplementaryBudget();

        $supplementaryBudget->setTitle(
            $createSupplementaryDto->getTitle()
        );

        $supplementaryBudget->setPid(
            $createSupplementaryDto->getRootSysFolderPid()
        );

        $supplementaryBudget->setSorting(
            $sorting
        );

        $createSupplementaryDto
            ->getBudget()
            ->addSupplementaryBudget($supplementaryBudget);

        $this->persistenceManager->update(
            $createSupplementaryDto->getBudget()
        );

        $info['process'] = time() - $info['start'];
        $info['record'] = $supplementaryBudget;

        return $info;
    }
}
