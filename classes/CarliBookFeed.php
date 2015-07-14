<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewBooksFeedReader
 *
 * @author bgarcia
 */
class CarliBookFeed extends RSSFeed {
    public function __construct() {
        parent::__construct("https://i-share.carli.illinois.edu/newbooks/newbooks.cgi?library=WHEdb&list=all&day=7&op=and&text=&lang=English&submit=RSS");

        $nitems = count($this->xml->channel->item);
        for ( $i=0; $i<$nitems; ++$i ) {
            $str = $this->xml->channel->item[$i]->description->asXML();
            $str = str_replace('&lt;B&gt;','',$str);
            $str = str_replace('&lt;/B&gt;','',$str);
            $str = str_replace('<description>','',$str);
            $str = str_replace('</description>','',$str);
            $fields = explode('&lt;BR/&gt;',$str);

            foreach($fields as $f) {
                if (stripos($f,'&lt;img border=')!==FALSE) //unwanted line
                    continue;

                //separate field name and field value
                $matches = array();
                if(preg_match("/^([-a-zA-Z_ ]*[a-zA-Z]{4}): /",$f,$matches)) {;
                    $fname = $matches[1];
                    $fvalue = str_replace($matches[0],'',$f);
                    $this->xml->channel->item[$i]->addChild($fname,$fvalue);
                }
            }
        }
    }
}