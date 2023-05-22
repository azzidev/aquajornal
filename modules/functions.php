<?php
    function clearString($string){
        $a = array("aquajornal","aqua","news","data","date","bcc","root","+","--","&","/","hash","mysql","insert","select","create","alter","delete","from","*","1=1",'""',"''","drop","DROP","table","TABLE");
        return str_replace($a, '',$string);
    }
?>