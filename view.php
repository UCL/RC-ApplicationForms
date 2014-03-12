<?php
include "auth_user_shim.php";

$page_title = "View Account Request";
include_once "header.php";
include_once "misc_functions.php";
include_once "mailmailer.php";
include_once "user.php";
include_once "requestpair.php";


$req_method = $_SERVER['REQUEST_METHOD'];

if ($req_method == "POST") {
    $req_account_request_id = $_POST['account_request_id'];
    $req_project_id         = $_POST['project_id'];
    $req_action             = $_POST['action_choice'];
    $post_comments          = $_POST['comments'];
} elseif ($req_method == "GET") {
    $req_account_request_id = $_GET['ida'];
    $req_project_id         = $_GET['idp'];
    $req_action             = $_GET["action"];
    $post_comments          = "(via a direct link)";
} else {
    die("Please stop trying to break our form.\n");
}


try{
    $current_user = new User($current_username);

    $request_pair = new RequestPair($req_project_id);
    if ($request_pair->is_valid() == FALSE) {
        echo "<h4>Invalid Request [Acct ID:{$req_account_request_id}, Proj ID:{$req_project_id}] . If you believe this is a mistake, please contact rc-support@ucl.ac.uk, pasting into the email the full address of this page.</h4>";
    } else {
        $taking_action = FALSE;
        if ($request_pair->can_be_approved_by($current_user)) {
            if ($req_action == "approve") {
                $result = $request_pair->approve_by($current_user, $post_comments);
                $user_mail_template_name = "new_account_request_approval";
                $rcps_mail_template_name = "rcps_notify_request_approval";
                $taking_action = TRUE;
            } elseif ($req_action == "decline") {
                $result = $request_pair->decline_by($current_user, $post_comments);
                $user_mail_template_name = "new_account_request_declined";
                $rcps_mail_template_name = "rcps_notify_request_declined";
                $taking_action = TRUE;
            }

            if ($taking_action == TRUE) {
                if ($result == TRUE) {
                    $mailer = new MailMailer();
                    $user_mail_result = $mailer->send_mail(
                                                $user_mail_template_name,
                                                $request_pair->user_email(), 
                                                array('recommendations'=>
                                                  $request_pair->services_text_from_work())
                                              );
                    $rcps_mail_result = $mailer->send_mail(
                                                $rcps_mail_template_name,
                                                "rc-support@ucl.ac.uk",
                                                array('acting_user' => $current_user->full_name(),
                                                      'acting_user_address' => $current_user->email_address(),
                                                      'override_replyto'    => $current_user->email_address(),
                                                      'comments'    => $post_comments,
                                                      'account_request_id'=> $req_account_request_id,
                                                      'project_id'  => $req_project_id,
                                                      'request_user'=> $request_pair->owner(),
                                                      'consortium'  => $request_pair->consortium(),
                                                      'action'      => $req_action,
                                                      'recommendations' => $request_pair->services_text_from_work()
                                                  )
                                              );
                    $mail_not_sent = "";
                    $mail_not_sent_array = array();
                    $overall_mail_result = $user_mail_result && $rcps_mail_result;
                    if ($user_mail_result != TRUE) { array_pop($mail_not_sent_array, "the user"); }
                    if ($rcps_mail_result != TRUE) { array_pop($mail_not_sent_array, "RC Support"); }
                    if ($overall_mail_result != TRUE) {
                        $mail_not_sent = " but a notification mail could not be sent to ".
                                         array_as_text_list($mail_not_sent_array, " or ") . 
                                         "Please contact rc-support@ucl.ac.uk.";
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
            
            if ($request_pair->last_status_text() == "submitted") { 
                $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                "   <form id=\"application_form\"" . 
                                "          action=\"view.php\" " .
                                "          method=\"post\"       " .
                                "          enctype=\"multipart/form-data\" ".
                                "     > " .
                                "   <input type=\"hidden\" " .
                                "          name=\"account_request_id\" " .
                                "          value=\"" . $req_account_request_id . "\" " .
                                "    />" .
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
                                "   <label for=\"decline_radio\"> " .
                                "      <input type=\"radio\" " .
                                "             id=\"decline_radio\" " .
                                "             name=\"action_choice\" " . 
                                "             value=\"decline\"> " .
                                "         Decline" .
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
                                "          title=\"Submit approve/decline.\" " .
                                "    />" .
                                "   </td></tr></table>" .
                                "   </form>" .
                                "</div>";
            }
        } else {
            $approval_div = "";
        }



        switch ($request_pair->last_status_text()) {
            case "submitted":
                echo "<p class='p'>This request was submitted on: ".$request_pair->last_status_time()."</p>";
                break;
            case "approved":
                echo "<p class='p'>This request was approved on: ".$request_pair->last_status_time()."</p>";
                break;
            case "declined":
                echo "<p class='p'>This request was declined on: ".$request_pair->last_status_time()."</p>";
                break;
            case "expired":
                echo "<p class='p'>This project expired on: ".$request_pair->last_status_time()."</p>";
                break;
            case "broken":
                echo "<p class='p'>This request was marked as broken on: ".$request_pair->last_status_time()."</p>";
                break;
            default:
                echo "<h4>This request is in an unexpected state: ". 
                     htmlspecialchars( $request_pair->last_status_text() ) . 
                     " Please contact rc-support@ucl.ac.uk and let them know.</h4>";
        }

        if ($current_user->is_superuser()) {
            echo "<p class='p'>This action was taken by " .
                 $request_pair->last_status_user() .
                 ", with comments: " .
                 htmlspecialchars($request_pair->last_status_comments()) .
                 "</p>";
        }

        echo $approval_div;

        echo $request_pair->as_table();
    }

} catch(\PDOException $ex){
  print("\n<p>" . $ex->getMessage() . "</p>\n");
}


include "footer.php";

?>
