<?php
if ( (FALSE == array_key_exists("PHP_AUTH_USER", $_SERVER)) ||
     (is_null($_SERVER["PHP_AUTH_USER"])) )
{
    if (array_key_exists("user", $_REQUEST)) {
        $current_username = $_REQUEST['user'];
    } else {
        $current_username = "ccaaxxx";
    }
} else {
    $current_username = $_SERVER['PHP_AUTH_USER'];
}
?>
