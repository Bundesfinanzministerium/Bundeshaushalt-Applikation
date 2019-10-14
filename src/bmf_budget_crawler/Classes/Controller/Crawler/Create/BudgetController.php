<?php
namespace PPKOELN\BmfBudgetCrawler\Controller\Crawler\Create;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\BudgetCrawlerDto;

class BudgetController extends ActionController
{
    /**
     * Budget repository
     *
     * @var \PPKOELN\BmfBudget\Domain\Repository\BudgetRepository
     * @inject
     */
    protected $budgetRepository;

    /**
     * Crawler repository
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository
     * @inject
     */
    protected $crawlerRepository;

    /**
     * Budget hook service
     *
     * @var \PPKOELN\BmfBudgetCrawler\Service\BudgetHookService
     * @inject
     */
    protected $hookService;

    /**
     * Index action
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\BudgetCrawlerDto $crawlerDto
     *
     * @return null
     */
    public function indexAction(
        BudgetCrawlerDto $crawlerDto = null
    ) {
        $crawlerDto = $crawlerDto === null ? new BudgetCrawlerDto() : $crawlerDto;

        $this->view->assign('hooks', $this->hookService->getHooksAsArray());
        $this->view->assign('dto', $crawlerDto);
        $this->view->assign('budgets', $this->budgetRepository->findAll());

        return null;
    }

    /**
     * Create action
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\BudgetCrawlerDto $crawlerDto
     * @return null
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function createAction(
        BudgetCrawlerDto $crawlerDto
    ) {
        $this->loopStructure($crawlerDto);

        $this->forward(
            'index',
            null,
            null,
            ['crawlerDto' => $crawlerDto]
        );

        return null;
    }

    /**
     * Loop through structures
     *
     * @param BudgetCrawlerDto $crawlerDto
     * @param BudgetCrawlerDto $crawlerNew
     *
     * @return null
     */
    protected function loopStructure(
        BudgetCrawlerDto $crawlerDto
    ) {
        $structures = $crawlerDto->getStructure();
        foreach ($structures as $structure) {
            $this->loopFlow(
                $crawlerDto,
                $structure
            );
        }
        return null;
    }

    /**
     * Loop through flow
     *
     * @param BudgetCrawlerDto $crawlerDto
     * @param BudgetCrawlerDto $crawlerNew
     *
     * @return null
     */
    protected function loopFlow(
        BudgetCrawlerDto $crawlerDto,
        $structure
    ) {
        $flows = $crawlerDto->getFlow();
        foreach ($flows as $flow) {
            $this->loopAccount(
                $crawlerDto,
                $structure,
                $flow
            );
        }
        return null;
    }

    /**
     * Loop through accounts
     *
     * @param BudgetCrawlerDto $crawlerDto
     * @param string $structure
     * @param string $flow
     * @return null
     * @throws \Exception
     */
    protected function loopAccount(
        BudgetCrawlerDto $crawlerDto,
        $structure,
        $flow
    ) {
        $accounts = $crawlerDto->getAccount();
        foreach ($accounts as $account) {
            $this->registerCrawler(
                $crawlerDto,
                $structure,
                $flow,
                $account
            );
        }
        return null;
    }

    /**
     * Register crawler
     *
     * @param BudgetCrawlerDto $crawlerDto
     *
     * @return null
     * @throws \Exception
     */
    protected function registerCrawler(
        BudgetCrawlerDto $crawlerDto,
        $structure,
        $flow,
        $account
    ) {

        if (!$hook = $this->hookService->getHooksByIndex($crawlerDto->getHook())) {
            throw new \Exception('Extension Hook not found');
        }

        $crawler = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler;
        $crawler->setExtTitle($hook->title);
        $crawler->setExtClass($hook->class);
        $crawler->setBudget($crawlerDto->getBudget());
        $crawler->setStructure($structure);
        $crawler->setFlow($flow);
        $crawler->setAccount($account);

        $this->crawlerRepository->add($crawler);

        $this->addFlashMessage(
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'flashMessage.crawler.create.message',
                'bmf_budget_crawler'
            ),
            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'flashMessage.crawler.create.headline',
                'bmf_budget_crawler'
            )
        );

        return null;
    }
}
