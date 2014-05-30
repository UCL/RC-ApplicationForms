<?php
include_once "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

$page_title = "Users";
include_once "includes/header.php";
include_once "includes/misc_functions.php";

if (array_key_exists('id',$_GET)) {
    $user_profile = UserProfile::from_db($_GET['id']);
    $mode = "id";
} elseif(array_key_exists('username',$_GET)) {
    $user_profile = UserProfile::from_db_by_name($_GET['username']);
    $mode = "username";
}

if (isset($user_profile)) {
    if ($user_profile == FALSE) {
        echo ("<h4>Could not find specified user.</h4>");
    } else {
        include "views/table_user.php";

        if ($mode == "username") {
            echo "<p class='p'>The above is the most recent entry for this username. Listed below are all entries with the same username, but only the newest one will be used.</p>\n";
            $user_profiles = UserProfile::get_all_for_one_username($_GET['username']);
            include "views/table_users.php";
        }
    }
} else {
    $actor = new SQLActor();
    $actor->connect();
    $user_profiles = UserProfile::get_all_from_db();
    include "views/table_users.php";
}

include_once "includes/footer.php";
