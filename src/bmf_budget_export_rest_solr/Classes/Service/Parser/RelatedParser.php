<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Service\Parser;

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use \TYPO3\CMS\Core\SingletonInterface;

class RelatedParser extends AbstractParser implements SingletonInterface
{

    public function get(\PPKOELN\BmfBudget\Domain\Model\Title $title = null)
    {
        return [
            'agency' => [
                [
                    'a' => $title->getSection()->getSection()->getAddress(),
                    'l' => $this->removeLn($title->getSection()->getSection()->getTitle())
                ],
                [
                    'a' => $title->getSection()->getAddress(),
                    'l' => $this->removeLn($title->getSection()->getTitle())
                ]
            ],
            'group' => [
                [
                    'a' => $title->getGroupe()->getGroupe()->getGroupe()->getAddress(),
                    'l' => $this->removeLn($title->getGroupe()->getGroupe()->getGroupe()->getTitle())
                ],
                [
                    'a' => $title->getGroupe()->getGroupe()->getAddress(),
                    'l' => $this->removeLn($title->getGroupe()->getGroupe()->getTitle())
                ],
                [
                    'a' => $title->getGroupe()->getAddress(),
                    'l' => $this->removeLn($title->getGroupe()->getTitle())
                ],
            ],
            'function' => [
                [
                    'a' => $title->getFunctione()->getFunctione()->getFunctione()->getAddress(),
                    'l' => $this->removeLn($title->getFunctione()->getFunctione()->getFunctione()->getTitle())
                ],
                [
                    'a' => $title->getFunctione()->getFunctione()->getAddress(),
                    'l' => $this->removeLn($title->getFunctione()->getFunctione()->getTitle())
                ],
                [
                    'a' => $title->getFunctione()->getAddress(),
                    'l' => $this->removeLn($title->getFunctione()->getTitle())
                ]
            ]
        ];
    }
}
