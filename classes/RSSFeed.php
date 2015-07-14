<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RSSReader
 *
 * @author bgarcia
 */
class RSSFeed {
    protected $xml; // SimpleXML

    public function __construct($url) {
        if (($xmlstr=file_get_contents($url))===FALSE)
            throw new BadFunctionCallException("Failed to fetch content from url: \n$url");
        //$this->xml = simplexml_load_string($xmlstr);
        $this->xml = new SimpleXMLElement($xmlstr);
    }

    public function items() {
        return $this->xml->channel->item;
    }

    public function display() {
        echo $this->xml->asXML();
    }
}