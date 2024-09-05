<?php

function _isCurl(){
    return function_exists('curl_version');
}

print_r(_isCurl());

?>