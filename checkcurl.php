<?php

function _isCurl(){
    return function_exists('curl_version');
}

_isCurl();

?>