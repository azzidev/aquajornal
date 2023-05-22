<?php
    function clearString($string){
        $a = array("aquajornal","aqua","news","data","date","bcc","root","+","--","&","/","hash","mysql","insert","select","create","alter","delete","from","*","1=1",'""',"''","drop","DROP","table","TABLE");
        return str_replace($a, '',$string);
    }
    
    function random_color_part() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    function decide_color($hex){
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        if (($r*0.299 + $g*0.587 + $b*0.114) > 186){
            return "black";
        }else{
            return "white";            
        }
    }
?>