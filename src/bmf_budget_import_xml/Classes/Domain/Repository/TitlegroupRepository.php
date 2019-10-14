<?php
namespace PPK\BmfBudgetImportXml\Domain\Repository;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;
use PPKOELN\BmfBudget\Domain\Model\Budget;
use PPKOELN\BmfBudget\Domain\Model\Section;
use PPKOELN\BmfBudget\Domain\Model\Titlegroup;

class TitlegroupRepository extends Repository
{
    /**
     * TitlegroupRepository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\TitlegroupRepository
     * @inject
     */
    protected $titlegroupRepository;

    /**
     * @param Budget $budget
     * @param Section $section
     * @param string $address
     * @param string $title
     * @param array $info
     * @return Titlegroup
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidNumberOfConstraintsException
     */
    public function create(
        Budget $budget,
        Section $section,
        $address = '',
        $title = '- dummytitle -',
        &$info = []
    ) {
        if ($titlegroup = $this->titlegroupRepository->findBySectionAndAddress($section, $address)) {
            if ($title !== '- dummytitle -' && $title !== $titlegroup->getTitle()) {
                $titlegroup->setTitle($title);
                $this->titlegroupRepository->update($titlegroup);
                $this->titlegroupRepository->persistenceManager->persistAll();
                $info['records']['titlegroup']['updated'][] = [
                    'budget' => $budget->getYear(),
                    'section' => $section->getAddress(),
                    'address' => $address,
                    'label' => $title
                ];
            }
            return $titlegroup;
        }

        $titlegroup = new Titlegroup();
        $titlegroup->setPid($budget->getPidTitlegroup());
        $titlegroup->setSection($section);
        $titlegroup->setTitle($title);
        $titlegroup->setAddress($address);
        $info['records']['titlegroup']['new'][] = [
            'budget' => $budget->getYear(),
            'section' => $section->getAddress(),
            'address' => $address,
            'label' => $title
        ];
        $this->titlegroupRepository->add($titlegroup);
        $this->titlegroupRepository->persistenceManager->persistAll();

        return $titlegroup;
    }
}
