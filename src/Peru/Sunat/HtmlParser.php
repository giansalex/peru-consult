<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/01/2018
 * Time: 21:36.
 */

namespace Peru\Sunat;

use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;

/**
 * Class HtmlParser.
 */
final class HtmlParser
{
    /**
     * Parse html to dictionary.
     *
     * @param $html
     *
     * @return array|false
     */
    public function parse($html)
    {
        $xp = $this->getXpathFromHtml($html);
        $table = $xp->query('./html/body/table[1]');

        if (0 == $table->length) {
            return false;
        }

        $nodes = $table->item(0)->childNodes;

        return $this->getKeyValues($nodes, $xp);
    }

    private function getKeyValues(DOMNodeList $nodes, DOMXPath $xp)
    {
        $dic = [];
        foreach ($nodes as $item) {
            /** @var $item DOMNode */
            if ($this->isNotElement($item)) {
                continue;
            }

            $this->setValuesFromNode($xp, $item, $dic);
        }

        return $dic;
    }

    private function setValuesFromNode(DOMXPath $xp, DOMNode $item, &$dic)
    {
        $isTitle = true;
        $title = '';
        foreach ($item->childNodes as $item2) {
            /** @var $item2 DOMNode */
            if ($this->isNotElement($item2)) {
                continue;
            }

            if ($isTitle) {
                $title = trim($item2->textContent);
                $isTitle = false;
            } else {
                $dic[$title] = $this->getContent($xp, $item2);
                $isTitle = true;
            }
        }
    }

    public static function getXpathFromHtml($html)
    {
        $dom = new DOMDocument();
        $prevState = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($prevState);

        return new DOMXPath($dom);
    }

    private function getContent(DOMXPath $xp, DOMNode $node)
    {
        $select = $xp->query('./select', $node);
        if ($select->length > 0) {
            $options = $select->item(0)->childNodes;

            return iterator_to_array($this->getValuesFromOption($options));
        }

        return trim($node->textContent);
    }

    private function getValuesFromOption(DOMNodeList $options)
    {
        foreach ($options as $opt) {
            /** @var $opt DOMNode */
            if ('option' != $opt->nodeName) {
                continue;
            }
            yield trim($opt->textContent);
        }
    }

    private function isNotElement(DOMNode $node)
    {
        return XML_ELEMENT_NODE !== $node->nodeType;
    }
}
