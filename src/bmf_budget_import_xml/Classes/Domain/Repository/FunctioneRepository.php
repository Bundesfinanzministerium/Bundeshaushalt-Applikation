<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

class FunctioneRepository extends Repository
{
    /**
     * FunctioneRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functioneRepository;

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     * @param string $address
     * @param string $title
     * @param array $info
     * @return bool|object|\PPKOELN\BmfBudget\Domain\Model\Functione
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function create(
        \PPKOELN\BmfBudget\Domain\Model\Budget $budget,
        $address = '',
        $title = '- dummytitle -',
        &$info = []
    ) {
        if (strlen($address) === 1) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($function = $this->functioneRepository->findByBudgetAndAddress($budget, $address)) {
                if ($title !== '- dummytitle -' && $title !== $function->getTitle()) {
                    $function->setTitle($title);
                    $this->functioneRepository->update($function);
                    $this->functioneRepository->persistenceManager->persistAll();
                    $info['records']['function']['updated'][] = [
                        'budget' => $budget->getYear(),
                        'address' => $address,
                        'label' => $title
                    ];
                }
                return $function;
            }
            $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
            $function->setPid($budget->getPidFunction());
            $function->setBudget($budget);
            $function->setAddress($address);
            $function->setTitle($title);
            $this->functioneRepository->add($function);
            $this->functioneRepository->persistenceManager->persistAll();
            $info['records']['function']['new'][] = [
                'budget' => $budget->getYear(),
                'address' => $address,
                'label' => $title
            ];
            return $function;
        } elseif (strlen($address) > 1) {
            // sub level
            $parent = $this->create($budget, substr($address, 0, strlen($address) - 1), '- dummytitle -', $info);
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($function = $this->functioneRepository->findByEntityAndAddress($parent, $address)) {
                if ($title !== '- dummytitle -' && $title !== $section->getTitle()) {
                    $function->setTitle($title);
                    $this->functioneRepository->update($function);
                    $this->functioneRepository->persistenceManager->persistAll();
                    $info['records']['function']['updated'][] = [
                        'budget' => $budget->getYear(),
                        'address' => $address,
                        'label' => $title
                    ];
                }
                return $function;
            }
            $function = new \PPKOELN\BmfBudget\Domain\Model\Functione();
            $function->setPid($budget->getPidFunction());
            $function->setFunctione($parent);
            $function->setAddress($address);
            $function->setTitle($title);
            $this->functioneRepository->add($function);
            $this->functioneRepository->persistenceManager->persistAll();
            $info['records']['function']['new'][] = [
                'budget' => $budget->getYear(),
                'address' => $address,
                'label' => $title
            ];
            return $function;
        }
    }
}
