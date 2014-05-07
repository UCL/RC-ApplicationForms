<?php
include "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";

$page_title = "Submitting Application";
include "includes/header.php";


$referrer_file_name = array_pop(explode("/", $_SERVER["HTTP_REFERER"])); 

$valid_referrers = array("apply.php", "renew.php");

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

    $new_project_request = ProjectRequest::from_request($request);
    $creation_result = $new_project_request->save_to_db($operator);

    if ($creation_result !== FALSE) {
        //TODO: Move mailer calls to within Project Requests
        $new_project_request->update_status($operator, 'submitted', 'automatically');

        $addresses_to_mail = $new_project_request->get_user_profile()->get_sponsor_email_address();
        /*
        $mailer = new MailMailer();
        $mail_result = $mailer->send_mail("new_account_for_approval", $addresses_to_mail, $request);

        if ($mail_result !== TRUE) {
            echo $strings["submit"]["err_could_not_send_mail"] . $mail_result."\n";
        } else {
            echo $strings["submit"]["success"];
        }
        */
    }

} catch(\PDOException $ex) {
    print("\n<p>" . $ex->getMessage() . "</p>\n");
}

include "includes/footer.php";

?>
