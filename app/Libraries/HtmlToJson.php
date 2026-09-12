<?php

namespace App\Libraries;


use DOMDocument;

class HtmlToJson {
    public $html;
    public $filter;

    function __construct($html, $filter) {
        $this->dom = new DOMDocument();
        $this->dom->loadHTML( $html );
        $this->jsonObj = array('form_tag_attrs'=>array(), 'form_values'=>array());
        $this->filter = $filter;
    }

    function recursivePair($element, $tagName) {
        if ( isset( $element->attributes ) ) {
            $nameAttr = $element->getAttribute('name');

            if ($nameAttr) {
                $this->jsonObj['form_values'][$nameAttr] = $element->getAttribute('value');
            }

            if ($element->nodeName === $tagName) {
                foreach ( $element->attributes as $attribute ) {
                    $this->jsonObj['form_tag_attrs'][ $attribute->name ] = $attribute->value;
                }
            }
        }

        if ( isset( $element->childNodes ) ) {
            foreach ( $element->childNodes as $subElement ) {
                $this->recursivePair( $subElement, $tagName );
            }
        }
    }

    function json() {
        $element = ($this->filter ? $this->dom->getElementsByTagName($this->filter)->item(0) : $this->dom->documentElement);

        $this->recursivePair($element, $this->filter);

        return $this->jsonObj;
    }
}
