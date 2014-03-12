<?php
if ( (FALSE == array_key_exists("PHP_AUTH_USER", $_SERVER)) ||
     (is_null($_SERVER["PHP_AUTH_USER"])) )
{
    if (is_null($_REQUEST['user'])) {
        $current_username = "ccaaxxx";
    } else {
        $current_username = $_REQUEST['user'];
    }
} else {
    $current_username = $_SERVER['PHP_AUTH_USER'];
}
?>
