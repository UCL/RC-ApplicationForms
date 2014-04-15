<?php

$page_title = "Submitting Application";
include "includes/header.php";
include_once "includes/MailMailer.php";
include_once "includes/SQLActor.php";
include_once "includes/strings.php";

$referrer_file_name = array_pop(explode("/", $_SERVER["HTTP_REFERER"])); 

$valid_referrers = array("apply.php", "renew.php");

if ( (! in_array($referrer_file_name, $valid_referrers, TRUE)) or
     ( $_SERVER['REQUEST_METHOD'] != "POST") )
{
    exit ("It is not permitted to use this page in this way.\n");
}

try {
    $actor = new SQLActor();
    $actor->connect();

    $request = $_POST;

    $applying_user = new UserProfile($request['username']);

    if ($applying_user->has_open_project_request()) {
        echo $strings["submit"]["err_open_proj_req"];
    } else {

        $new_project_request = ProjectRequest::from_request($request);
        $creation_result = $new_project_request->save_to_db();

        if ($creation_result !== FALSE) {
            //TODO: Move mailer calls to within Project Requests
            $actor->mark_request_status($request['created_id'], 
                                        $request['project']['created_id'],
                                        $request['username'], 
                                        'submitted', 
                                        "automatically");

            $addresses_to_mail = $new_project_request->get_user_profile()->get_sponsor_email_address();

            $mailer = new MailMailer();
            $mail_result = $mailer->send_mail("new_account_for_approval", $addresses_to_mail, $request);

            if ($mail_result !== TRUE) {
                echo $strings["submit"]["err_could_not_send_mail"] . $mail_result."\n";
                print_r($request);
            } else {
                echo $strings["submit"]["success"];
            }
        } 
    }
} catch(\PDOException $ex) {
    print("\n<p>" . $ex->getMessage() . "</p>\n");
}


//echo "referrer_file_name = $referrer_file_name\n";

//$arr = get_defined_vars();
//print_r($arr["_SERVER"]);
//print_r($arr["_REQUEST"]);
//print_r($arr["_POST"]);

//print_r(array_keys(get_defined_vars()));

include "includes/footer.php";

?>
