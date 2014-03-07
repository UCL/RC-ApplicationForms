<?php
if ( (FALSE == array_key_exists("PHP_AUTH_USER", $_SERVER)) ||
     (is_null($_SERVER["PHP_AUTH_USER"])) )
{
    $current_username = "ccaaxxx";
} else {
    $current_username = $_SERVER['PHP_AUTH_USER'];
}
?>
