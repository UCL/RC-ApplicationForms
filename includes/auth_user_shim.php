<?php

/**
 * This file converts an Apache auth'd user into a username in $current_username.
 *
 * If the is_dev SiteSetting variable is set to TRUE, it also allows a developer to set what user they are
 *  on a per-query basis. This... Might be dangerous. I'm hoping not!
 */
if ( (class_exists(SiteSettings) && (SetSiteSettings::is_dev == TRUE) ) { 

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
} else {
    $current_username = $_SERVER['PHP_AUTH_USER'];
}

?>
