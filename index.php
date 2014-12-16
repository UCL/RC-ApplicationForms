<?php

include "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

$page_title = "RCPS Account Services";
include "includes/header.php";

/*
 *
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
 */

?>

<ul>
    <li><a href="apply.php">Apply for an Account</a></li>
    <li><a href="view_project_request.php">View the List of Projects</a></li>
    <li><a href="view_user.php">View the List of Users</a></li>
    <li><a href="publications.php">Tell us about a publication or grant that uses (or will use) Legion</a></li>
    <li><a href="mailto:rc-support@ucl.ac.uk">Contact Us</a></li>
</ul>

<?php
  include "includes/footer.php";
?>