<?php
namespace PPKOELN\BmfBudgetCrawler\Domain\Model;

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Queue extends AbstractEntity
{
    /**
     * Crawler
     *
     * @var \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler
     */
    protected $crawler;

    /**
     * Address
     *
     * @var string
     */
    protected $address = '';

    /**
     * Status
     *
     * @var string
     */
    protected $status = '';

    /**
     * Result message
     *
     * @var string
     */
    protected $result = '';

    /**
     * Error flag
     *
     * @var integer
     */
    protected $error = 0;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Returns the crawler
     *
     * @return \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }

    /**
     * Sets the crawler
     *
     * @param \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
     * @return void
     */
    public function setCrawler(
        \PPKOELN\BmfBudgetCrawler\Domain\Model\Crawler $crawler
    ) {
        $this->crawler = $crawler;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status
     *
     * @param string $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the result
     *
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets the result
     *
     * @param string $result
     * @return void
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Returns the error
     *
     * @return integer $error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the error
     *
     * @param integer $error
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Returns the error message
     *
     * @return string $errorMessage
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets the error message
     *
     * @param string $errorMessage
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}
