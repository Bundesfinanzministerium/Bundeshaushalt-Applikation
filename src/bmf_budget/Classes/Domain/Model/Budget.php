<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class Budget extends AbstractInfo
{

    /**
     * Actual title property
     *
     * @var string
     */
    protected $titleActual = '';

    /**
     * Target title property
     *
     * @var string
     */
    protected $titleTarget = '';

    /**
     * Year property
     *
     * @var string
     * @validate NotEmpty
     */
    protected $year = '';

    /**
     * Total actual income for section(s)
     *
     * @var float
     */
    protected $sectionActualIncome;

    /**
     * Total actual expenses for section(s)
     *
     * @var float
     */
    protected $sectionActualExpenses;

    /**
     * Total target income for section(s)
     *
     * @var float
     */
    protected $sectionTargetIncome;

    /**
     * Total target expenses for section(s)
     *
     * @var float
     */
    protected $sectionTargetExpenses;

    /**
     * Total actual income for function(s)
     *
     * @var float
     */
    protected $functionActualIncome;

    /**
     * Total actual expenses for function(s)
     *
     * @var float
     */
    protected $functionActualExpenses;

    /**
     * Total target income for function(s)
     *
     * @var float
     */
    protected $functionTargetIncome;

    /**
     * Total target expenses for function(s)
     *
     * @var float
     */
    protected $functionTargetExpenses;

    /**
     * Total actual income for group(s)
     *
     * @var float
     */
    protected $groupActualIncome;

    /**
     * Total actual expenses for group(s)
     *
     * @var float
     */
    protected $groupActualExpenses;

    /**
     * Total target income for group(s)
     *
     * @var float
     */
    protected $groupTargetIncome;

    /**
     * Total target expenses for group(s)
     *
     * @var float
     */
    protected $groupTargetExpenses;

    /**
     * PageID for sysfolder titles
     *
     * @var int
     */
    protected $pidTitle = 0;

    /**
     * PageID for sysfolder sections
     *
     * @var int
     */
    protected $pidSection = 0;

    /**
     * PageID for sysfolder functions
     *
     * @var int
     */
    protected $pidFunction = 0;

    /**
     * PageID for sysfolder groups
     *
     * @var int
     */
    protected $pidGroup = 0;

    /**
     * PageID for sysfolder budgetgroup
     *
     * @var int
     */
    protected $pidBudgetgroup = 0;

    /**
     * PageID for sysfolder titlegroups
     *
     * @var int
     */
    protected $pidTitlegroup = 0;

    /**
     * PageID for sysfolder supplementary-budget
     *
     * @var int
     */
    protected $pidSupplementaryBudget = 0;

    /**
     * PageID for sysfolder supplementary-title
     *
     * @var int
     */
    protected $pidSupplementaryTitle = 0;

    /**
     * Section child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section>
     * @cascade remove
     * @lazy
     */
    protected $sections;

    /**
     * Group child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe>
     * @cascade remove
     * @lazy
     */
    protected $groups;

    /**
     * Function child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione>
     * @cascade remove
     * @lazy
     */
    protected $functions;

    /**
     * SupplementaryBudget child entities
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget>
     * @cascade remove
     */
    protected $supplementaryBudgets;

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
        $this->sections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->groups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->functions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->supplementaryBudgets = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the actual title
     *
     * @return string $titleActual
     */
    public function getTitleActual()
    {
        return $this->titleActual;
    }

    /**
     * Sets the actual title
     *
     * @param string $titleActual The actual title property to be added
     *
     * @return void
     */
    public function setTitleActual($titleActual)
    {
        $this->titleActual = $titleActual;
    }

    /**
     * Returns the target title
     *
     * @return string $titleTarget
     */
    public function getTitleTarget()
    {
        return $this->titleTarget;
    }

    /**
     * Sets the target title
     *
     * @param string $titleTarget The target title property to be added
     *
     * @return void
     */
    public function setTitleTarget($titleTarget)
    {
        $this->titleTarget = $titleTarget;
    }

    /**
     * Returns the year
     *
     * @return string $year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets the year
     *
     * @param string $year The year property to be added
     *
     * @return void
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Returns the actual income for sections
     *
     * @return float $sectionActualIncome
     */
    public function getSectionActualIncome()
    {
        return $this->sectionActualIncome;
    }

    /**
     * Sets the actual income for sections
     *
     * @param float $sectionActualIncome The actual income to be added for sections
     *
     * @return void
     */
    public function setSectionActualIncome($sectionActualIncome)
    {
        $this->sectionActualIncome = $sectionActualIncome;
    }

    /**
     * Returns the actual expenses for sections
     *
     * @return float $sectionActualExpenses
     */
    public function getSectionActualExpenses()
    {
        return $this->sectionActualExpenses;
    }

    /**
     * Sets the actual expenses for sections
     *
     * @param float $sectionActualExpenses The actual expenses to be added for sections
     *
     * @return void
     */
    public function setSectionActualExpenses($sectionActualExpenses)
    {
        $this->sectionActualExpenses = $sectionActualExpenses;
    }

    /**
     * Returns the target income for sections
     *
     * @return float $sectionTargetIncome
     */
    public function getSectionTargetIncome()
    {
        return $this->sectionTargetIncome;
    }

    /**
     * Sets the target income for sections
     *
     * @param float $sectionTargetIncome The target income to be added for sections
     *
     * @return void
     */
    public function setSectionTargetIncome($sectionTargetIncome)
    {
        $this->sectionTargetIncome = $sectionTargetIncome;
    }

    /**
     * Returns the target expenses for sections
     *
     * @return float $sectionTargetExpenses
     */
    public function getSectionTargetExpenses()
    {
        return $this->sectionTargetExpenses;
    }

    /**
     * Sets the target expenses for sections
     *
     * @param float $sectionTargetExpenses The target expenses to be added for sections
     *
     * @return void
     */
    public function setSectionTargetExpenses($sectionTargetExpenses)
    {
        $this->sectionTargetExpenses = $sectionTargetExpenses;
    }

    /**
     * Returns the actual income for functions
     *
     * @return float $functionActualIncome
     */
    public function getFunctionActualIncome()
    {
        return $this->functionActualIncome;
    }

    /**
     * Sets the actual income for functions
     *
     * @param float $functionActualIncome The actual income to be added for functions
     *
     * @return void
     */
    public function setFunctionActualIncome($functionActualIncome)
    {
        $this->functionActualIncome = $functionActualIncome;
    }

    /**
     * Returns the actual expenses for functions
     *
     * @return float $functionActualExpenses
     */
    public function getFunctionActualExpenses()
    {
        return $this->functionActualExpenses;
    }

    /**
     * Sets the actual expenses for functions
     *
     * @param float $functionActualExpenses The actual expenses to be added for functions
     *
     * @return void
     */
    public function setFunctionActualExpenses($functionActualExpenses)
    {
        $this->functionActualExpenses = $functionActualExpenses;
    }

    /**
     * Returns the target income for functions
     *
     * @return float $functionTargetIncome
     */
    public function getFunctionTargetIncome()
    {
        return $this->functionTargetIncome;
    }

    /**
     * Sets the target income for functions
     *
     * @param float $functionTargetIncome The target income to be added for functions
     *
     * @return void
     */
    public function setFunctionTargetIncome($functionTargetIncome)
    {
        $this->functionTargetIncome = $functionTargetIncome;
    }

    /**
     * Returns the target expenses for functions
     *
     * @return float $functionTargetExpenses
     */
    public function getFunctionTargetExpenses()
    {
        return $this->functionTargetExpenses;
    }

    /**
     * Sets the target expenses for functions
     *
     * @param float $functionTargetExpenses The target expenses to be added for functions
     *
     * @return void
     */
    public function setFunctionTargetExpenses($functionTargetExpenses)
    {
        $this->functionTargetExpenses = $functionTargetExpenses;
    }

    /**
     * Returns the actual income for groups
     *
     * @return float $groupActualIncome
     */
    public function getGroupActualIncome()
    {
        return $this->groupActualIncome;
    }

    /**
     * Sets the actual income for groups
     *
     * @param float $groupActualIncome The actual income to be added for groups
     *
     * @return void
     */
    public function setGroupActualIncome($groupActualIncome)
    {
        $this->groupActualIncome = $groupActualIncome;
    }

    /**
     * Returns the actual expenses for groups
     *
     * @return float $groupActualExpenses
     */
    public function getGroupActualExpenses()
    {
        return $this->groupActualExpenses;
    }

    /**
     * Sets the actual expenses for groups
     *
     * @param float $groupActualExpenses The actual expenses to be added for groups
     *
     * @return void
     */
    public function setGroupActualExpenses($groupActualExpenses)
    {
        $this->groupActualExpenses = $groupActualExpenses;
    }

    /**
     * Returns the target income for groups
     *
     * @return float $groupTargetIncome
     */
    public function getGroupTargetIncome()
    {
        return $this->groupTargetIncome;
    }

    /**
     * Sets the target income for groups
     *
     * @param float $groupTargetIncome The target income to be added for groups
     *
     * @return void
     */
    public function setGroupTargetIncome($groupTargetIncome)
    {
        $this->groupTargetIncome = $groupTargetIncome;
    }

    /**
     * Returns the target expenses for groups
     *
     * @return float $groupTargetExpenses
     */
    public function getGroupTargetExpenses()
    {
        return $this->groupTargetExpenses;
    }

    /**
     * Sets the target expenses for groups
     *
     * @param float $groupTargetExpenses The target expenses to be added for groups
     *
     * @return void
     */
    public function setGroupTargetExpenses($groupTargetExpenses)
    {
        $this->groupTargetExpenses = $groupTargetExpenses;
    }

    /**
     * Returns the sysfolder-pid for titles
     *
     * @return int $pidTitle
     */
    public function getPidTitle()
    {
        return $this->pidTitle;
    }

    /**
     * Sets the sysfolder-pid for titles
     *
     * @param int $pidTitle The sysfolder-pid to be added for titles
     *
     * @return void
     */
    public function setPidTitle($pidTitle)
    {
        $this->pidTitle = $pidTitle;
    }

    /**
     * Returns the sysfolder-pid for sections
     *
     * @return int $pidSection
     */
    public function getPidSection()
    {
        return $this->pidSection;
    }

    /**
     * Sets the sysfolder-pid for sections
     *
     * @param int $pidSection The sysfolder-pid to be added for sections
     *
     * @return void
     */
    public function setPidSection($pidSection)
    {
        $this->pidSection = $pidSection;
    }

    /**
     * Returns the sysfolder-pid for functions
     *
     * @return int $pidFunction
     */
    public function getPidFunction()
    {
        return $this->pidFunction;
    }

    /**
     * Sets the sysfolder-pid for functions
     *
     * @param int $pidFunction The sysfolder-pid to be added for functions
     *
     * @return void
     */
    public function setPidFunction($pidFunction)
    {
        $this->pidFunction = $pidFunction;
    }

    /**
     * Returns the sysfolder-pid for groups
     *
     * @return int $pidGroup
     */
    public function getPidGroup()
    {
        return $this->pidGroup;
    }

    /**
     * Sets the sysfolder-pid for groups
     *
     * @param int $pidGroup The sysfolder-pid to be added for groups
     *
     * @return void
     */
    public function setPidGroup($pidGroup)
    {
        $this->pidGroup = $pidGroup;
    }

    /**
     * Returns the sysfolder-pid for budget-group
     *
     * @return int $pidBudgetgroup
     */
    public function getPidBudgetgroup()
    {
        return $this->pidBudgetgroup;
    }

    /**
     * Sets the sysfolder-pid for budget-group
     *
     * @param int $pidBudgetgroup The sysfolder-pid to be added for budget-groups
     *
     * @return void
     */
    public function setPidBudgetgroup($pidBudgetgroup)
    {
        $this->pidBudgetgroup = $pidBudgetgroup;
    }

    /**
     * Returns the sysfolder-pid for title-group
     *
     * @return int $pidTitlegroup
     */
    public function getPidTitlegroup()
    {
        return $this->pidTitlegroup;
    }

    /**
     * Sets the sysfolder-pid for title-group
     *
     * @param int $pidTitlegroup The sysfolder-pid to be added for title-groups
     *
     * @return void
     */
    public function setPidTitlegroup($pidTitlegroup)
    {
        $this->pidTitlegroup = $pidTitlegroup;
    }

    /**
     * Returns the sysfolder-pid for supplementary-budget
     *
     * @return int $pidSupplementaryBudget
     */
    public function getPidSupplementaryBudget()
    {
        return $this->pidSupplementaryBudget;
    }

    /**
     * Sets the sysfolder-pid for supplementary-budget
     *
     * @param int $pidSupplementaryBudget The sysfolder-pid to be added for spplementary-budget
     *
     * @return void
     */
    public function setPidSupplementaryBudget($pidSupplementaryBudget)
    {
        $this->pidSupplementaryBudget = $pidSupplementaryBudget;
    }

    /**
     * Returns the sysfolder-pid for supplementary-title
     *
     * @return int $pidSupplementaryTitle
     */
    public function getPidSupplementaryTitle()
    {
        return $this->pidSupplementaryTitle;
    }

    /**
     * Sets the sysfolder-pid for supplementary-title
     *
     * @param int $pidSupplementaryTitle The sysfolder-pid to be added for spplementary-title
     *
     * @return void
     */
    public function setPidSupplementaryTitle($pidSupplementaryTitle)
    {
        $this->pidSupplementaryTitle = $pidSupplementaryTitle;
    }

    /**
     * Adds a section
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $section The section to be added
     *
     * @return void
     */
    public function addSection(\PPKOELN\BmfBudget\Domain\Model\Section $section)
    {
        $this->sections->attach($section);
    }

    /**
     * Removes a section
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Section $sectionToRemove The section to be removed
     *
     * @return void
     */
    public function removeSection(\PPKOELN\BmfBudget\Domain\Model\Section $sectionToRemove)
    {
        $this->sections->detach($sectionToRemove);
    }

    /**
     * Returns sections
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section> $sections
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Sets sections
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Section> $sections
     *        A recordset of sections to be added
     *
     * @return void
     */
    public function setSections(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Adds a group
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $group The group to be added
     *
     * @return void
     */
    public function addGroup(\PPKOELN\BmfBudget\Domain\Model\Groupe $group)
    {
        $this->groups->attach($group);
    }

    /**
     * Removes a group
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Groupe $groupToRemove The group to be removed
     *
     * @return void
     */
    public function removeGroup(\PPKOELN\BmfBudget\Domain\Model\Groupe $groupToRemove)
    {
        $this->groups->detach($groupToRemove);
    }

    /**
     * Returns groups
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe> $groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Sets groups
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Groupe> $groups
     *        A recordset of groups to be added
     *
     * @return void
     */
    public function setGroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Adds a function
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $function The function to be added
     *
     * @return void
     */
    public function addFunction(\PPKOELN\BmfBudget\Domain\Model\Functione $function)
    {
        $this->functions->attach($function);
    }

    /**
     * Removes a function
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\Functione $functionToRemove The function to be removed
     *
     * @return void
     */
    public function removeFunction(\PPKOELN\BmfBudget\Domain\Model\Functione $functionToRemove)
    {
        $this->functions->detach($functionToRemove);
    }

    /**
     * Returns functions
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione> $functions
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Sets functions
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\Functione> $functions
     *        A recordset of functions to be added
     *
     * @return void
     */
    public function setFunctions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $functions)
    {
        $this->functions = $functions;
    }

    /**
     * Adds a supplementary-budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudget The supplementary-budget
     *        to be added
     *
     * @return void
     */
    public function addSupplementaryBudget(\PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudget)
    {
        $this->supplementaryBudgets->attach($supplementaryBudget);
    }

    /**
     * Removes a supplementary-budget
     *
     * @param \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudgetToRemove The supplementary-budget
     *        to be removed
     *
     * @return void
     */
    public function removeSupplementaryBudget(
        \PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget $supplementaryBudgetToRemove
    ) {
        $this->supplementaryBudgets->detach($supplementaryBudgetToRemove);
    }

    /**
     * Returns supplementary-budgets
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget>
     * $supplementaryBudgets
     */
    public function getSupplementaryBudgets()
    {
        return $this->supplementaryBudgets;
    }

    /**
     * Sets supplementary-budgets
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PPKOELN\BmfBudget\Domain\Model\SupplementaryBudget>
     *        $supplementaryBudgets A recordset of supplementary-budgets to be added
     * @return void
     */
    public function setSupplementaryBudgets(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $supplementaryBudgets)
    {
        $this->supplementaryBudgets = $supplementaryBudgets;
    }



    public function getTitle($account = '')
    {
        $account = ucfirst($account);
        return isset($this->{'title' . $account}) ? $this->{'title' . $account} : false;
    }

    public function getRevenue($structure = '', $account = '', $flow = '')
    {
        $structure = strtolower($structure);
        $account = ucfirst($account);
        $flow = ucfirst($flow);
        return isset($this->{$structure . $account . $flow}) ? $this->{$structure . $account . $flow} : false;
    }
}
