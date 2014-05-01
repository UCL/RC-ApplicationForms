<?php
echo "<h2>Warning: Operator Auth Shim in place. Remove from production.</h2>";

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
