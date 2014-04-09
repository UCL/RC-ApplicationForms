<?php

$page_title = "Submitting Application";
include "includes/header.php";
include_once "includes/MailMailer.php";
include_once "includes/SQLActor.php";

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
    if ($actor->does_user_have_existing_account_request('a'.$request['username'])) {
        //TODO: Change this: users need to be able to submit another request if they are declined.
        echo "<p class='p'><em>You already have an account request ". 
             "in progress -- you cannot submit another. If you have".
             " realised that you made a significant mistake, or for".
             " any other problem with the process, please contact ".
             "rc-support@ucl.ac.uk.</em></p>\n";
    } else {
        $creation_result = $actor->create_new_account_request($request);
        if ($creation_result !== FALSE) {
            // Add some result information we want to let us id the request
            $request['created_id'] = $creation_result['account_request_id']; 
            $request['project']['created_id'] = $creation_result['project_request_id']; 
            $request['consortium_name'] = $actor->get_consortium_name($request['project']['consortium_id']);

            $actor->mark_request_status($request['created_id'], 
                                        $request['project']['created_id'],
                                        $request['username'], 
                                        'submitted', 
                                        "automatically");

            $addresses_to_mail = $actor->get_consortium_leaders_to_mail($request['project']['consortium_id']);

            $mailer = new MailMailer();
            $mail_result = $mailer->send_mail("new_account_for_approval", $addresses_to_mail, $request);
            if ($mail_result !== TRUE) {
                echo "<h4>There was a problem mailing out approval requests. ".
                     "Please contact rc-support@ucl.ac.uk mentioning this ".
                     "problem, and pasting the following into your e-mail:".
                     "</h4>\n".$mail_result."\n";
                print_r($request);
            } else {
                echo "<p class='p'>Successfully mailed requests for approval. If you do not receive any further information within 3 days, please contact rc-support@ucl.ac.uk.</p>\n";
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
