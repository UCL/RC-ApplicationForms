<?php
include_once "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

$page_title = "Project Requests";
include_once "includes/header.php";
include_once "includes/misc_functions.php";

if (array_key_exists('id',$_GET)) {
    $project_request = ProjectRequest::from_db($_GET['id']);
}

if (isset($project_request)) {
    if ($project_request == FALSE) {
        echo ("<h4>Could not find specified project.</h4>");
    } else {
        include "views/table_project_request.php";
    }
} else {
    $actor = new SQLActor();
    $actor->connect();
    $project_requests = ProjectRequest::get_all_from_db();
    include "views/table_project_requests.php";
}

include_once "includes/footer.php";
