<?php

declare(strict_types=1);

namespace Peru\Sunat\Parser;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use Generator;
use Peru\Sunat\HtmlParserInterface;

class HtmlRecaptchaParser implements HtmlParserInterface
{
    /**
     * Parse html to dictionary.
     *
     * @param string $html
     *
     * @return array|false
     */
    public function parse(string $html)
    {
        $xp = XpathLoader::getXpathFromHtml($html);
        $table = $xp->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' list-group ')]");

        if (0 == $table->length) {
            return false;
        }

        $nodes = $table->item(0)->childNodes;

        return $this->getKeyValues($nodes, $xp);
    }

    private function getKeyValues(DOMNodeList $nodes, DOMXPath $xp): array
    {
        $dic = [];
        foreach ($nodes as $item) {
            /** @var $item DOMNode */
            if ($this->isNotElement($item)) {
                continue;
            }

            $this->setKeyValuesFromNode($xp, $item, $dic);
        }

        return $dic;
    }

    private function setKeyValuesFromNode(DOMXPath $xp, DOMNode $item, &$dic)
    {
        $keys = $xp->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' list-group-item-heading ')]", $item);
        $values = $xp->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' list-group-item-text ')]", $item);

        $isHeadRow = $values->length === 0 && $keys->length === 2;
        if ($isHeadRow) {
            $title = trim($keys->item(0)->textContent);
            $dic[$title] = trim($keys->item(1)->textContent);

            return;
        }

        for ($i = 0; $i < $keys->length; $i++) {
            $title = trim($keys->item($i)->textContent);

            if ($values->length > $i) {
                $dic[$title] = trim($values->item($i)->textContent);
            } else {
                $dic[$title] = iterator_to_array($this->getValuesFromTable($xp, $item));
            }
        }
    }

    private function getValuesFromTable(DOMXPath $xp, DOMNode $item): Generator
    {
        $rows = $xp->query('.//table/tbody/tr/td', $item);

        foreach ($rows as $item) {
            /** @var $item DOMNode */
            yield trim($item->textContent);
        }
    }

    private function isNotElement(DOMNode $node)
    {
        return XML_ELEMENT_NODE !== $node->nodeType;
    }
}
