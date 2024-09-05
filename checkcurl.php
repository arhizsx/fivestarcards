<?php
if (function_exists('curl_init')) {
    echo "cURL is installed!";
} else {
    echo "cURL is not installed.";
}
if (extension_loaded('xml')) {
    echo "XML is enabled!";
} else {
    echo "XML is not enabled.";
}
?>
