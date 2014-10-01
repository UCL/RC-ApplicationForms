<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-10-01
 * Time: 11:16
 */

header("application/json");

//include_once "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

include_once "includes/misc_functions.php";

try{
    $actor = new SQLActor();
    $actor->connect();

    $project_requests = ProjectRequest::get_all_from_db($actor);

    $themes_by_user = array();

    $users_by_theme = array();

    /** @var ProjectRequest $one_project_request */

    foreach ($project_requests as $one_project_request) {
        if ($one_project_request->get_last_status()->get_text() != "approved") {
            continue;
        }

        $pr_user = $one_project_request->get_user_profile()->get_username();
        $pr_ref_cat = $one_project_request->get_research_theme();

        if (array_key_exists($pr_user, $themes_by_user)) {
            array_push($themes_by_user[$pr_user], $pr_ref_cat);
        } else {
            $themes_by_user[$pr_user] = array($pr_ref_cat);
        }

        if (array_key_exists($pr_ref_cat, $users_by_theme)) {
            array_push($users_by_theme[$pr_ref_cat], $pr_user);
        } else {
            $users_by_theme[$pr_ref_cat] = array($pr_user);
        }

    }

    $all_results = array("themes_by_user" => $themes_by_user, "users_by_theme" => $users_by_theme);

    $output_string = json_encode($all_results);

    print($output_string);

} catch(\PDOException $ex){
    print($ex->getMessage());
}