<?php
include_once "includes/auth_user_shim.php";
include_once "includes/autoload_definition.php";
include_once "includes/config.php";

$page_title = "View Account Request";
include_once "includes/header.php";
include_once "includes/misc_functions.php";



$req_method = $_SERVER['REQUEST_METHOD'];

if ($req_method == "POST") {
    $req_project_id         = $_POST['project_id'];
    $req_action             = $_POST['action_choice'];
    $post_comments          = $_POST['comments'];
} elseif ($req_method == "GET") {
    $req_project_id         = $_GET['idp'];
    $req_action             = array_key_exists('action', $_GET)?$_GET["action"]:NULL;
    $post_comments          = "(via a direct link)";
} else {
    die("Please stop trying to break our form.\n");
}


try{
    $current_user = new Operator($current_username);

    $project_request = ProjectRequest::from_db($req_project_id);
    if ($project_request->is_valid() == FALSE) {
        echo "<h4>Invalid Request ". (($req_project_id == "")?"[No project id]":"[Proj ID:{$req_project_id}]") . ". If you believe this is a mistake, please contact rc-support@ucl.ac.uk, pasting into the email the full address of this page.</h4>";
    } else {
        $taking_action = FALSE;
        if ($project_request->can_be_approved_by($current_user)) {
            if ($req_action == "approve") {
                $result = $project_request->approve_by($current_user, $post_comments);
                $user_mail_template_name = "new_account_request_approved";
                $rcps_mail_template_name = "rcps_notify_request_approved";
                $taking_action = TRUE;
            } elseif ($req_action == "reject") {
                $result = $project_request->reject_by($current_user, $post_comments);
                $user_mail_template_name = "new_account_request_rejected";
                $rcps_mail_template_name = "rcps_notify_request_rejected";
                $taking_action = TRUE;
            } else {
                $result = FALSE;
            }

            if ($taking_action == TRUE) {
                if ($result == TRUE) {
                    $mailer = new MailMailer();
                    $user_mail_result = $mailer->send_mail(
                                                $user_mail_template_name,
                                                $project_request->get_user_profile()->get_user_email(),
                                                $project_request,
                                                $project_request->get_user_profile(),
                                                $current_user
                                              );
                    $rcps_mail_result = $mailer->send_mail(
                                                $rcps_mail_template_name,
                                                "rc-support@ucl.ac.uk",
                                                $project_request,
                                                $project_request->get_user_profile(),
                                                $current_user
                                            );
                    $mail_not_sent = "";
                    $mail_not_sent_array = array();
                    $overall_mail_result = $user_mail_result && $rcps_mail_result;
                    if ($user_mail_result != TRUE) { array_push($mail_not_sent_array, "the user"); }
                    if ($rcps_mail_result != TRUE) { array_push($mail_not_sent_array, "RC Support"); }
                    if ($overall_mail_result != TRUE) {
                        $mail_not_sent = " but a notification mail could not be sent to ".
                                         array_as_text_list($mail_not_sent_array, " or ") .
                                         ". Please contact rc-support@ucl.ac.uk.";
                    }
                    $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                    "Request {$req_action}d{$mail_not_sent}".
                                    "</div>";
                } elseif ($result == FALSE) {
                    $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                    "There was an error updating the request. ".
                                    "Please contact rc-support@ucl.ac.uk.".
                                    "</div>";
                }
            }

            if ($project_request->get_last_status()->get_text() == "submitted") {
                $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                "   <form id=\"application_form\"" .
                                "          action=\"view_application.php\" " .
                                "          method=\"post\"       " .
                                "          enctype=\"multipart/form-data\" ".
                                "     > " .
                                "   <input type=\"hidden\" " .
                                "          name=\"project_id\" " .
                                "          value=\"" . $req_project_id . "\" " .
                                "    />" .
                                "   <table style='margin-left:auto;margin-right:auto;'>" .
                                "   <tr><td>" .
                                "   <label for=\"approve_radio\"> " .
                                "      <input type=\"radio\" " .
                                "             id=\"approve_radio\" " .
                                "             name=\"action_choice\" " .
                                "             value=\"approve\"> " .
                                "         Approve" .
                                "   </label>" .
                                "   </td><td>" .
                                "   <label for=\"reject_radio\"> " .
                                "      <input type=\"radio\" " .
                                "             id=\"reject_radio\" " .
                                "             name=\"action_choice\" " .
                                "             value=\"reject\"> " .
                                "         Reject" .
                                "   </label>" .
                                "   </td></tr>" .
                                "   <tr><td colspan=2>" .
                                "   <textarea name=\"comments\" " .
                                "             rows=2 " .
                                "             cols=60 " .
                                "             placeholder=\"Please enter any additional comments here." .
                                " These will not be seen by the applicant.\" " .
                                "    ></textarea>" .
                                "   </td></tr><tr><td colspan=2>" .
                                "   <input type=\"submit\" " .
                                "          id=\"form_submit_button\" " .
                                "          value=\"Submit\" " .
                                "          title=\"Submit approve/reject.\" " .
                                "    />" .
                                "   </td></tr></table>" .
                                "   </form>" .
                                "</div>";
            }
        } else {
            if ( ($req_action == "approve") || ($req_action == "reject") ) {
                echo "<h4>You have not been permitted to perform this action. " .
                     "Please contact rc-support@ucl.ac.uk if you believe you should have been.</h4>\n";
            }
            $approval_div = "";
        }

        if (in_array($project_request->get_last_status()->get_text(),
            array("submitted","approved","rejected","expired","broken"))) {

            echo "<p class='p'>This request (id=" .
                 $project_request->get_id() .
                 ") was " .
                 $project_request->get_last_status()->get_text() .
                 " on: " .
                 $project_request->get_last_status()->get_update_time() .
                 "</p>";
        } else {
                echo "<h4>This request is in an unexpected state: ".
                     htmlspecialchars( $project_request->get_last_status()->get_text() ) .
                     " Please contact rc-support@ucl.ac.uk and let them know.</h4>";
        }

        if ($current_user->is_superuser()) {
            echo "<p class='p'>This action was taken by " .
                 $project_request->get_last_status()->get_acting_user() .
                 ", with comments: " .
                 htmlspecialchars($project_request->get_last_status()->get_comment()) .
                 "</p>";
        }

        echo $approval_div;

        include "views/table_project_request.php";

    }

} catch(\PDOException $ex){
  print("\n<p>" . $ex->getMessage() . "</p>\n");
}


include "includes/footer.php";
