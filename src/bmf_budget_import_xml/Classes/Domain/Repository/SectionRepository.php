<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

class SectionRepository extends Repository
{
    /**
     * SectionRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\SectionRepository
     * @inject
     */
    protected $sectionRepository;

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     * @param string $address
     * @return bool|object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function get(\PPKOELN\BmfBudget\Domain\Model\Budget $budget, $address = '')
    {
        return $this->sectionRepository->findByBudgetAndAddress($budget, $address);
    }

    /**
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     * @param string $address
     * @param string $title
     * @param array $info
     * @return bool|\PPKOELN\BmfBudget\Domain\Model\Section
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
        if (strlen($address) === 2) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($section = $this->sectionRepository->findByBudgetAndAddress($budget, $address)) {
                if ($title !== '- dummytitle -' && $title !== $section->getTitle()) {
                    $section->setTitle($title);
                    $this->sectionRepository->update($section);
                    $this->sectionRepository->persistenceManager->persistAll();
                    $info['records']['section']['updated'][] = ['address' => $address, 'label' => $title];
                }
                return $section;
            }

            $section = new \PPKOELN\BmfBudget\Domain\Model\Section();
            $section->setPid($budget->getPidSection());
            $section->setBudget($budget);
            $section->setAddress($address);
            $section->setTitle($title);
            $this->sectionRepository->add($section);
            $this->sectionRepository->persistenceManager->persistAll();
            $info['records']['section']['new'][] = ['address' => $address, 'label' => $title];
            return $section;
        } elseif (strlen($address) === 4) {
            // sub level
            $parent = $this->create($budget, substr($address, 0, 2), '- dummytitle -', $info);

            /** @var \PPKOELN\BmfBudget\Domain\Model\Section $section */
            if ($section = $this->sectionRepository->findByEntityAndAddress($parent, $address)) {
                if ($title !== '- dummytitle -' && $title !== $section->getTitle()) {
                    $section->setTitle($title);
                    $this->sectionRepository->update($section);
                    $this->sectionRepository->persistenceManager->persistAll();
                    $info['records']['chapter']['updated'][] = ['address' => $address, 'label' => $title];
                }
                return $section;
            }
            $section = new \PPKOELN\BmfBudget\Domain\Model\Section();
            $section->setPid($budget->getPidSection());
            $section->setSection($parent);
            $section->setAddress($address);
            $section->setTitle($title);
            $this->sectionRepository->add($section);
            $this->sectionRepository->persistenceManager->persistAll();
            $info['records']['chapter']['new'][] = ['address' => $address, 'label' => $title];
            return $section;
        }
        return false;
    }
}
