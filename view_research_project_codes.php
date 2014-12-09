<?php
include_once "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

$page_title = "Research Project Codes";
include_once "includes/header.php";
include_once "includes/misc_functions.php";

echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js\"></script>";
echo "<script src=\"js/jquery.tablesorter.min.js\"></script>";
echo "<script src=\"js/jquery.metadata.js\"></script>";


$actor = new SQLActor();
$actor->connect();
$project_codes = ResearchProjectCode::get_all_from_db($actor);
include "views/table_research_project_codes.php";


include_once "includes/footer.php";