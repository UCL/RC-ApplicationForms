<?php

include "header.php";
include "sqlactor.php";

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
    if ($actor->does_user_have_existing_account_request($request['userid'])) {
        echo "<p class='p'><em>You already have an account request in progress -- you cannot submit another. If you have realised that you made a significant mistake, or for any other problem with the process, please contact rc-support@ucl.ac.uk.</em></p>\n";
    } else {
        $result = $actor->create_new_account_request($request);
        if ($result === TRUE) {
            $people_to_mail = $actor->get_consortium_leaders_to_mail($request['project']['consortium_id']);
            $mailer = new MailMailer();
            $result = $mailer->new_account_for_approval($people_to_mail, $request);
            if ($result !== TRUE) {
                echo "<h4>There was a problem mailing out approval requests. Please contact rc-support@ucl.ac.uk mentioning this problem, and pasting the following into your e-mail:</h4>\n".$result."\n".array_to_html($request);
            } else {
                echo "<p class='p'>Successfully mailed requests for approval. If you do not receive any further information within 3 days, please contact rc-support@ucl.ac.uk.</p>\n";
            }
        } else {
            echo "<h4>Error in creating the account request: $result</h4>\n";
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

include "footer.php";

?>
