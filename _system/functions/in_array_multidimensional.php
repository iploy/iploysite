<?php

function in_array_multidimensional($needle, $haystack) {
    foreach ($haystack as $item) {
        if ($item === $needle || (is_array($item) && in_array_multidimensional($needle, $item))) {
            return true;
        }
    }
    return false;
}


?>