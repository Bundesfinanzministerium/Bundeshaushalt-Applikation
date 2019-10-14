<?php
namespace PPKOELN\BmfBudgetCrawler\Controller\Crawler\Create;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\TimelineCrawlerDto;

class TimelineController extends ActionController
{
    /**
     * Crawler repository
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Repository\CrawlerRepository
     * @inject
     */
    protected $crawlerRepository;

    /**
     * Timeline hook service
     *
     * @var \PPKOELN\BmfBudgetCrawler\Service\TimelineHookService
     * @inject
     */
    protected $hookService;

    /**
     * Index action
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\TimelineCrawlerDto $crawlerDto
     *
     * @return null
     */
    public function indexAction(
        TimelineCrawlerDto $crawlerDto = null
    ) {
        $crawlerDto = $crawlerDto === null ? new TimelineCrawlerDto() : $crawlerDto;
        $this->view->assign('hooks', $this->hookService->getHooksAsArray());
        $this->view->assign('dto', $crawlerDto);
        return null;
    }

    /**
     * Create action
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Dto\TimelineCrawlerDto $crawlerDto
     * @return null
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function createAction(
        TimelineCrawlerDto $crawlerDto
    ) {
        $this->registerCrawler($crawlerDto);
        $this->forward(
            'index',
            null,
            null,
            ['crawlerDto' => $crawlerDto]
        );
    }

    /**
     * Register crawler
     *
     * @param TimelineCrawlerDto $crawlerDto
     * @return null
     * @throws \Exception
     */
    protected function registerCrawler(
        TimelineCrawlerDto $crawlerDto
    ) {

        if (!$hook = $this->hookService->getHooksByIndex($crawlerDto->getHook())) {
            throw new \Exception('Extension Hook not found');
        }

        $crawler = new \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler;
        $crawler->setExtTitle($hook->title);
        $crawler->setExtClass($hook->class);

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
