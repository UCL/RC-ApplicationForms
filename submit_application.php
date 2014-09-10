<?php
include "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/strings.php";
include_once "includes/config.php";

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
    $new_project_request = ProjectRequest::from_request($request, $actor);
    $creation_result = $new_project_request->save_to_db($operator);

    if ($creation_result !== FALSE) {
        //TODO: Move mailer calls to within Project Requests
        $new_project_request->update_status($operator, 'submitted', '(from the form)');

        $new_user_profile = $new_project_request->get_user_profile();


        if ($new_user_profile->get_sponsor_username() == "") {
            $addresses_to_mail = array($new_user_profile->get_user_email());
            $template_name = "new_account_for_self_approval";
        } else {
            $addresses_to_mail = array($new_user_profile->get_sponsor_email_address());
            $template_name = "new_account_for_approval";
        }

        $mailer = new MailMailer();
        $mail_result = $mailer->send_mail($template_name, $addresses_to_mail, $new_project_request, $new_project_request->get_user_profile(), $operator);

        if ($mail_result !== TRUE) {
            echo $strings["submit"]["err_could_not_send_mail"] . $mail_result."\n <pre>";
            var_dump($_POST);
            echo "</pre>\n";
        } else {
            echo $strings["submit"]["success"];
        }

    }

} catch(\PDOException $ex) {
    print("\n<p>" . $ex->getMessage() . "</p>\n");;
}

include "includes/footer.php";

?>
