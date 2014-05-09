<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-05-07
 * Time: 11:28
 */

include "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";

$page_title = "Submitting Publications";
include "includes/header.php";


$referrer_file_name = array_pop(explode("/", $_SERVER["HTTP_REFERER"]));

$valid_referrers = array("publications.php");

if ( (! in_array($referrer_file_name, $valid_referrers, TRUE)) or
    ( $_SERVER['REQUEST_METHOD'] != "POST") )
{
    exit ("It is not permitted to use this page in this way.\n");
}

$operator = new Operator($current_username);

try {
    $actor = new SQLActor();
    $actor->connect();

    $request = $_POST;

    echo "<ul>";


    foreach ($request['publications'] as $one_publication_array) {
        if ($one_publication_array['url'] != "") {
            $one_publication = Publication::from_array($one_publication_array);
            $result = $one_publication->save_to_db($operator);
            echo "<li><a href=\"" .
                $one_publication->get_url() .
                "\">" . $one_publication->get_url() .
                "</a> " .
                ($one_publication->is_notable() ? "*" : "") .
                ($result ? " saved." : " could not be saved.") .
                "</li>";
        }
    }
    echo "</ul>";

} catch(\PDOException $ex) {
    print("\n<p>" . $ex->getMessage() . "</p>\n");
}

include "includes/footer.php";
