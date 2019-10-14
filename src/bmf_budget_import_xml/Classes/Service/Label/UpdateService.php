<?php
namespace PPK\BmfBudgetImportXml\Service\Label;

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use PPK\BmfBudgetImportXml\Domain\Model\Xml\Label;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\SingletonInterface;
use PPK\BmfBudgetImportXml\Domain\Model\Dto\Label\UpdateLabelDto;

class UpdateService extends AbstractService implements SingletonInterface
{
    /**
     * Function Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\FunctioneRepository
     * @inject
     */
    protected $functioneRepository;

    /**
     * Group Repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\GroupeRepository
     * @inject
     */
    protected $groupeRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     * @inject
     */
    protected $persistenceManager;

    /**
     * Log Service
     *
     * @var \PPKOELN\BmfBudget\Service\Backend\LogService
     * @inject
     */
    protected $logService;

    /**
     * @param UpdateLabelDto $updateLabelDto
     * @return null
     */
    public function process(UpdateLabelDto $updateLabelDto)
    {
        $this->logService->initialize($updateLabelDto->getSession());
        $info = [
            'start' => time(),
            'length' => 0,
            'count' => 0,
            'records' => [
                'processed' => [
                    'functions' => 0,
                    'groups' => 0,
                ],
                'errors' => []
            ]
        ];

        // initialize model
        $labels = new Label($updateLabelDto->getFile());

        // get funktionen
        $funktionen = $labels->getFunktionen();

        // get gruppen
        $gruppen = $labels->getGruppen();

        // update total records
        $info['length'] = count($funktionen) + count($gruppen);

        // Iterate through funktionen
        $this->iterateThroughEntities(
            $updateLabelDto->getBudget()->getFunctions(),
            $this->functioneRepository,
            $funktionen
        );
        $this->persistenceManager->persistAll();

        // Iterate through gruppen
        $this->iterateThroughEntities(
            $updateLabelDto->getBudget()->getGroups(),
            $this->groupeRepository,
            $gruppen
        );
        $this->persistenceManager->persistAll();

        $info['count'] = count($funktionen) + count($gruppen);
        $info['records']['processed']['functions'] = count($funktionen);
        $info['records']['processed']['groups'] = count($gruppen);


        $this->logService->close(
            [
            'bmf_budget_import_xml.label_update' => [
                'count' => count($funktionen) + count($gruppen),
                'current' => count($funktionen) + count($gruppen)
            ]
            ]
        );
        $info['process'] = time() - $info['start'];
        return $info;
    }

    /**
     * Recursive iteration through entities
     *
     * @param $entities
     * @param $repository
     * @param $data
     * @param null $info
     * @return array|null
     */
    protected function iterateThroughEntities($entities, &$repository, &$data, &$info = null)
    {
        if (!isset($info)) {
            $info = ['cnt' => 0, 'error' => []];
        }
        foreach ($entities as $entity) {
            $childs = null;
            if ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Functione) {
                $childs = $entity->getFunctions();
            } elseif ($entity instanceof \PPKOELN\BmfBudget\Domain\Model\Groupe) {
                $childs = $entity->getGroups();
            }
            if (count($childs) > 0) {
                $this->iterateThroughEntities($childs, $repository, $data, $info);
            }
            $address = $entity->getAddress();
            if (isset($data['idx-' . $address])) {
                $entity->setTitle($data['idx-' . $address]['label']);
                $repository->update($entity);
                $info['cnt']++;
            } else {
                $info['error'][] = $address;
            }
        }
        return $info;
    }
}
