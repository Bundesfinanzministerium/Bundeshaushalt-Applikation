<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractAddress extends AbstractValue
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
     * @param string $address The address property to be added
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
     * @param string $title The title property to be added
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
