<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Title extends AbstractValue
{

    /**
     * Address property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $address = '';

    /**
     * Title property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * Flow property possible values are:
     *        "e" : income (german: eingang)
     *        "a" : expenses (german: ausgang)
     *
     * @var string
     */
    protected $flow = 0;

    /**
     * Flexible property
     *
     * @var bool
     */
    protected $flexible = false;

    /**
     * Actual page property
     *
     * @var int
     */
    protected $actualPage = 0;

    /**
     * Actual page link property
     *
     * @var int
     */
    protected $actualPageLink = 0;

    /**
     * Target page property
     *
     * @var int
     */
    protected $targetPage = 0;

    /**
     * Target page link property
     *
     * @var int
     */
    protected $targetPageLink = 0;

    /**
     * Check if values are initialized
     *
     * @var bool
     */
    protected $currentValuesInit = false;

    /**
     * Actual income including Supplementary: Budget and Title
     *
     * @var float
     */
    protected $currentActualIncome;

    /**
     * Actual expenses including Supplementary: Budget and Title
     *
     * @var float
     */
    protected $currentActualExpenses;

    /**
     * Target income including Supplementary: Budget and Title
     *
     * @var float
     */
    protected $currentTargetIncome;

    /**
     * Target expenses including Supplementary: Budget and Title
     *
     * @var float
     */
    protected $currentTargetExpenses;

    /**
     * Budget corresponding parent entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budget
     */
    protected $budget;

    /**
     * Section corresponding parent entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Section
     */
    protected $section;

    /**
     * Function corresponding parent entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Functione
     * @lazy
     */
    protected $functione;

    /**
     * Group corresponding parent entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Groupe
     * @lazy
     */
    protected $groupe;

    /**
     * Budgetgroup child entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Budgetgroup
     */
    protected $budgetgroup;

    /**
     * Titlegroup child entity
     *
     * @var \PPKOELN\BmfBudget\Domain\Model\Titlegroup
     */
    protected $titlegroup;

    /**
     * Supplementarie child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     * @cascade remove
     */
    protected $supplementaries;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->supplementaries = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Preparing current values
     *
     * @return void
     */
    protected function initCurrentValues()
    {

        $values = [
            [
                'income' => $this->targetIncome,
                'expenses' => $this->targetExpenses
            ]
        ];

        if (count($this->supplementaries) > 0) {
            // supplementaries are designed only for target budgets
            foreach ($this->supplementaries as $supplementary) {
                if ($supplementaryBudget = $supplementary->getSupplementaryBudget()) {
                    $index = $supplementaryBudget->getSorting();
                    $values[$index]['income'] = $supplementary->getTargetIncome();
                    $values[$index]['expenses'] = $supplementary->getTargetExpenses();
                }
            }
            krsort($values);
        }

        $values = reset($values);

        $this->currentTargetIncome = $values['income'];
        $this->currentTargetExpenses = $values['expenses'];

        $this->currentActualIncome = $this->actualIncome;
        $this->currentActualExpenses = $this->actualExpenses;
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
     * @param string $address The corresponding address (string, 9 chars like "010123201")
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title The correspondig title (string, like "Beteiligung der Länder an der Deutschen...")
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the flow
     *
     * @return int $flow
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * Sets the Flow
     *
     * @param string $flow The flow to be added. Only "e" (income) or "a" (expenses) are allowed
     *
     * @return void
     */
    public function setFlow($flow)
    {
        $this->flow = $flow;
    }

    /**
     * Returns the Flexible
     *
     * @return bool $flexible
     */
    public function getFlexible()
    {
        return $this->flexible;
    }

    /**
     * Sets the Flexible
     *
     * @param bool $flexible The flexible value to be changed to
     *
     * @return void
     */
    public function setFlexible($flexible)
    {
        $this->flexible = $flexible;
    }

    /**
     * Returns the boolean state of Flexible
     *
     * @return bool
     */
    public function isFlexible()
    {
        return $this->flexible;
    }

    /**
     * Returns the actual page
     *
     * @return int $actualPage
     */
    public function getActualPage()
    {
        return $this->actualPage;
    }

    /**
     * Sets the actual page
     *
     * @param int $actualPage Corresponding actual pdf page
     *
     * @return void
     */
    public function setActualPage($actualPage)
    {
        $this->actualPage = $actualPage;
    }

    /**
     * Returns the actual page link
     *
     * @return int $actualPageLink
     */
    public function getActualPageLink()
    {
        return $this->actualPageLink;
    }

    /**
     * Sets the actual page link
     *
     * @param int $actualPageLink Corresponding actual pdf page
     *
     * @return void
     */
    public function setActualPageLink($actualPageLink)
    {
        $this->actualPageLink = $actualPageLink;
    }

    /**
     * Returns the target page
     *
     * @return int $targetPage
     */
    public function getTargetPage()
    {
        return $this->targetPage;
    }

    /**
     * Sets the target page
     *
     * @param int $targetPage Corresponding target pdf page
     *
     * @return void
     */
    public function setTargetPage($targetPage)
    {
        $this->targetPage = $targetPage;
    }

    /**
     * Returns the target page link
     *
     * @return int $targetPageLink
     */
    public function getTargetPageLink()
    {
        return $this->targetPageLink;
    }

    /**
     * Sets the target page link
     *
     * @param int $targetPageLink Corresponding target pdf page
     *
     * @return void
     */
    public function setTargetPageLink($targetPageLink)
    {
        $this->targetPageLink = $targetPageLink;
    }

    /**
     * Returns the parent budget entity
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Budget $budget
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets the parent budget entity
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budget $budget The budget entity to be added
     *
     * @return void
     */
    public function setBudget(\PPKOELN\BmfBudget\Domain\Model\Budget $budget)
    {
        $this->budget = $budget;
    }

    /**
     * Adds a supplementary-title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementary The supplementary-title to be added
     * @return void
     */
    public function addSupplementary(\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementary)
    {
        $this->supplementaries->attach($supplementary);
    }

    /**
     * Removes a supplementary-title
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryToRemove The supplementary-title
     *        to be removed
     * @return void
     */
    public function removeSupplementary(\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementaryToRemove)
    {
        $this->supplementaries->detach($supplementaryToRemove);
    }

    /**
     * Returns all child supplementaries
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     *         $supplementaries
     */
    public function getSupplementaries()
    {
        return $this->supplementaries;
    }

    /**
     * @param SupplementaryBudget $SupplementaryBudget
     *
     * @return bool|SupplementaryTitle
     */
    public function getSupplementaryBySupplementaryBudget(
        \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $SupplementaryBudget
    ) {
        foreach ($this->supplementaries as $supplementary) {
            /** @var \PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle $supplementary */
            if ($supplementary->getSupplementarybudget() === $SupplementaryBudget) {
                return $supplementary;
            }
        }
        return false;
    }

    /**
     * Sets all child supplementaries
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryTitle>
     *        $supplementaries A recordset of supplementaries to be added
     * @return void
     */
    public function setSupplementaries(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $supplementaries)
    {
        $this->supplementaries = $supplementaries;
    }

    /**
     * Returns current actual income
     *
     * @return float
     */
    public function getCurrentActualIncome()
    {
        if (!$this->currentValuesInit) {
            $this->initCurrentValues();
            $this->currentValuesInit = true;
        }
        return $this->currentActualIncome;
    }

    /**
     * Returns current actual expenses
     *
     * @return float
     */
    public function getCurrentActualExpenses()
    {
        if (!$this->currentValuesInit) {
            $this->initCurrentValues();
            $this->currentValuesInit = true;
        }
        return $this->currentActualExpenses;
    }

    /**
     * Returns current target income
     *
     * @return float
     */
    public function getCurrentTargetIncome()
    {
        if (!$this->currentValuesInit) {
            $this->initCurrentValues();
            $this->currentValuesInit = true;
        }
        return $this->currentTargetIncome;
    }

    /**
     * Returns current target expenses
     *
     * @return float
     */
    public function getCurrentTargetExpenses()
    {
        if (!$this->currentValuesInit) {
            $this->initCurrentValues();
            $this->currentValuesInit = true;
        }
        return $this->currentTargetExpenses;
    }

    /**
     * Returns parent section
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param Section $section
     */
    public function setSection(\PPKOELN\BmfBudget\Domain\Model\Section $section)
    {
        $this->section = $section;
    }

    /**
     * Returns parent section
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Functione
     */
    public function getFunctione()
    {
        return $this->functione;
    }

    /**
     * @param Functione $function
     */
    public function setFunctione(\PPKOELN\BmfBudget\Domain\Model\Functione $function)
    {
        $this->functione = $function;
    }

    /**
     * Returns parent groupe
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param Groupe $groupe
     */
    public function setGroupe(\PPKOELN\BmfBudget\Domain\Model\Groupe $groupe)
    {
        $this->groupe = $groupe;
    }

    /**
     * Returns child budgetgroup
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup
     */
    public function getBudgetgroup()
    {
        return $this->budgetgroup;
    }

    /**
     * Sets child budgetgroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup
     *
     * @return void
     */
    public function setBudgetgroup(\PPKOELN\BmfBudget\Domain\Model\Budgetgroup $budgetgroup = null)
    {
        $this->budgetgroup = $budgetgroup;
    }

    /**
     * Returns child titlegroup
     *
     * @return \PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup
     */
    public function getTitlegroup()
    {
        return $this->titlegroup;
    }

    /**
     * Sets child titlegroup
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup
     *
     * @return void
     */
    public function setTitlegroup(\PPKOELN\BmfBudget\Domain\Model\Titlegroup $titlegroup = null)
    {
        $this->titlegroup = $titlegroup;
    }

    /**
     * Returns titlenumber
     *
     * @return string $titlenumber
     */
    public function getTitlenumber()
    {
        return substr($this->address, 4, 5);
    }

    /**
     * @param string $account
     * @param string $flow
     *
     * @return bool
     */
    public function getCurrentRevenue($account = '', $flow = '')
    {

        if (!$this->currentValuesInit) {
            $this->initCurrentValues();
            $this->currentValuesInit = true;
        }

        $account = ucfirst($account);
        $flow = ucfirst($flow);

        return isset($this->{'current' . $account . $flow})
            ? $this->{'current' . $account . $flow} === null ?: $this->{'current' . $account . $flow}
            : false;
    }
}
