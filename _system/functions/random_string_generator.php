<?php

function random_string_generator($stringlen) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "" ;    
    for ($p = 0; $p < $stringlen; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

?>
