<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-05-06
 * Time: 15:03
 */

include "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";

$page_title = "Publications";
include "includes/header.php";

$operator_user_profile = UserProfile::from_db_by_name($current_username);

if ($operator_user_profile === FALSE) {
    echo "<p class='p'>".
        "Your user profile could not be retrieved from the database," .
        " to associate with your publications. Please submit an application" .
        " before you attempt to submit publications." .
        "</p>";
} else {
    include "forms/form_publications.php";
}


include "includes/footer.php";
