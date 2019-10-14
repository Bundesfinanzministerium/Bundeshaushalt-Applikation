<?php
namespace PPKOELN\BmfBudget\Domain\Model;

/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class AbstractInfo extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Info image property
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $infoImage;

    /**
     * Info text property
     *
     * @var string
     */
    protected $infoText = '';

    /**
     * Info link property
     *
     * @var string
     */
    protected $infoLink = '';

    /**
     * Returns the info image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $infoImage
     */
    public function getInfoImage()
    {
        return $this->infoImage;
    }

    /**
     * Sets the info image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $infoImage The image to be added
     *
     * @return void
     */
    public function setInfoImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $infoImage)
    {
        $this->infoImage = $infoImage;
    }

    /**
     * Returns the info text
     *
     * @return string $infoText
     */
    public function getInfoText()
    {
        return $this->infoText;
    }

    /**
     * Sets the info text
     *
     * @param string $infoText The text to be added
     *
     * @return void
     */
    public function setInfoText($infoText)
    {
        $this->infoText = $infoText;
    }

    /**
     * Returns the info link
     *
     * @return string $infoLink
     */
    public function getInfoLink()
    {
        return $this->infoLink;
    }

    /**
     * Sets the info link
     *
     * @param string $infoLink The link to be added
     *
     * @return void
     */
    public function setInfoLink($infoLink)
    {
        $this->infoLink = $infoLink;
    }
}
