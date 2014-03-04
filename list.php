<?php
include "auth_user_replacement.php";

$page_title = "View Account Request";
include "header.php";
include "sqlactor.php";
include "misc_functions.php";
include "mailmailer.php";



class RequestPair {
    private $account_request_id;
    private $project_id;
    private $account_request;
    private $project;
    private $actor;
    private $valid;

    public function __construct($con_account_request_id, $con_project_id) {
        if (is_null($con_account_request_id) || is_null($con_project_id)) {
            // Shortcut if either is null
            $this->valid=FALSE;
            return FALSE;
        }
        
        $this->actor=new SQLActor();
        $this->actor->connect();
        $this->account_request_id = $con_account_request_id;
        $this->project_id = $con_project_id;

        $request_pair = $this->actor->get_request_pair($this->account_request_id, $this->project_id);

        if ($request_pair == FALSE) {
            $this->valid=FALSE;
            return FALSE;
        } else {
            $this->account_request = $request_pair[0];
            $this->project = $request_pair[1];
            $this->valid=TRUE;
            return TRUE;
        }
    }

    public function is_valid() {
        return $this->valid;
    }

    public function can_be_approved_by ($user) {
        if ($user->is_superuser() || 
            $user->is_leader_for($this->project['consortium_id']) ) 
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function approve_by ($user, $comments="") {
        if (! $this->can_be_approved_by($user)) {
            die("Permissions error.\n");
        } else {
            return $this->actor->mark_request_status(
                $this->account_request_id,
                $this->project_id,
                $user->username(),
                "approved",
                $comments
                );
        }
    }

    public function decline_by ($user, $comments="") {
        if (! $this->can_be_approved_by($user)) {
            die("Permissions error.\n");
        } else {
            return $this->actor->mark_request_status(
                $this->account_request_id,
                $this->project_id,
                $user->username(),
                "declined",
                $comments
                );
        }
    }

    public function owner() {
        return $this->project['username'];
    }

    public function user_email() {
        return $this->account_request['user_email'];
    }

    public function consortium() {
        // Should shunt this out to Project class ifwhen further classified
        return $this->actor->get_consortium_name($this->project['consortium_id']);
    }

    public function last_status_text() {
        return $this->actor->get_last_status_text($this->project_id);
    }

    public function last_status_time() {
        return $this->actor->get_last_status_time($this->project_id);
    }

    public function last_status_user() {
        return $this->actor->get_last_status_user($this->project_id);
    }

    public function get_approval_link() {
        return "<a href=\"".
               "list.php?ida=" . $this->account_request_id .
               "&idp=" . $this->project_id .
               "&action=approve".
               "\">Approve this Request</a>\n";
    }

    public function services_text_from_work() {
        $services_array = array();
        $work_service_mapping = array(
            'work_type_basic'       => 'Legion',
            'work_type_array'       => 'Legion',
            'work_type_multithread' => 'Legion',
            'work_type_all_the_ram' => 'Legion',
            'work_type_small_mpi'   => 'Legion',
            'work_type_mid_mpi'     => 'Iridis',
            'work_type_large_mpi'   => 'Iridis',
            'work_type_small_gpu'   => 'Emerald',
            'work_type_large_gpu'   => 'Emerald'
        );
        foreach ($work_service_mapping as $work_type => $service) {
            if ($this->project[$work_type] == TRUE) {
                array_push($services_array, $service);
            }
        }
        return array_as_text_list(array_unique($services_array));
    }

    public function as_list_table_row() {
        $html = table_keyval( 
                    $this->account_request['forenames'] . " " . 
                    $this->account_request['user_surname'],
                    $this->get_approval_link() 
                );
        return $html;
    }

    public function as_table() {
        $html = 
          "<table class='silvatable grid'><tr class='odd'><td colspan=2><strong>User</strong></td></tr>"
        // If you ever get around to class-ifying Project and Account_Request, this should call those instead. :/
         . table_keyval("User id", $this->account_request['username'])
         . table_keyval("User Name", $this->account_request['user_forenames']." ".
                                      $this->account_request['user_surname'])
         . table_keyval("User e-mail", $this->account_request['user_email'])
         . table_keyval("Supervisor Name", $this->account_request['supervisor_name'])
         . table_keyval("Supervisor e-mail", $this->account_request['supervisor_email'])

         . "</table><table class='silvatable grid'><tr class='odd'><td colspan=2><strong>Project</strong></td></tr>"
         . table_keyval("PI", $this->project['pi_email'])
         . table_keyval("Consortium Requested", $this->consortium())
         . table_keyval("Project Description", $this->project['work_description'], 1, TRUE)
         . table_keyval("Applications Required", $this->project['applications_description'], 1, TRUE)
         . table_keyval("Work Required", $this->project['work_required_collated'], 1, TRUE)
         . table_keyval("Unusual Technical Requirements", $this->project['weird_tech_description'], 1, TRUE)
         . table_keyval("Collaboration (if any)", $this->project['collaboration_collated'], 1, TRUE)
         . "</table>"
         ;
        return $html;
    }
}
    
class User {
    private $id;
    private $username;
    private $full_name;
    private $super_special_rainbow_pegasus_powers;
    private $receives_emails;
    private $email_address;

    private $actor;

    public function __construct($username) {
        $actor = new SQLActor();
        $actor->connect();
        $user_info = $actor->get_user_info($username);

        if ($user_info == FALSE) {
            // ... then a user does not have any special permissions
            $this->id=0;
            $this->username=$username;
            $this->full_name="(user full name not in permissions db)";
            $this->super_special_rainbow_pegasus_powers=FALSE;
            $this->receives_emails=FALSE;
            $this->email_address="";
        } else {
            $this->id=$user_info['id'];
            $this->username=$user_info['username'];
            $this->full_name=$user_info['full_name'];
            $this->super_special_rainbow_pegasus_powers=$user_info['super_special_rainbow_pegasus_powers'];
            $this->receives_emails=$user_info['receives_emails'];
            $this->email_address=$user_info['email_address'];
        }
    }

    public function is_superuser() {
        if ($this->super_special_rainbow_pegasus_powers == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_leader_for($consortium_id) {
        $leaderships = $this->actor->get_user_id_consortium_permissions($this->id);
        if (in_array($consortium_id, $leaderships)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function username() {
        return $this->username;
    }
}

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
    $actor = new SQLActor();
    $current_user = new User($current_username);

    $request_pair = new RequestPair($req_account_request_id, $req_project_id);
    if ($request_pair->is_valid() == FALSE) {
        echo "<h4>Invalid Request [{$req_account_request_id},{$req_project_id}] . If you believe this is a mistake, please contact rc-support@ucl.ac.uk, pasting into the email the full address of this page.</h4>";
    } else {

        if ($request_pair->can_be_approved_by($current_user)) {
            if ($req_action == "approve") {
                $result = $request_pair->approve_by($current_user, $post_comments);
                $mail_template_name = "new_account_request_approval";
                $taking_action = TRUE;
            } elseif ($req_action == "decline") {
                $result = $request_pair->decline_by($current_user, $post_comments);
                $mail_template_name = "new_account_request_declined";
                $taking_action = TRUE;
            }

            if ($taking_action == TRUE) {
                if ($result == TRUE) {
                    $mailer = new MailMailer();
                    $mail_result = $mailer->send_mail(
                                                $mail_template_name,
                                                $request_pair->user_email(), 
                                                array('recommendations'=>
                                                  $request_pair->services_text_from_work())
                                              );
                    if ($mail_result != TRUE) {
                        $mail_not_sent = " but notification mails could not be sent. Please contact rc-support@ucl.ac.uk.";
                    } else {
                        $mail_not_sent = "";
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
                                "          action=\"list.php\" " .
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

        echo $approval_div;

        echo $request_pair->as_table();
    }

} catch(\PDOException $ex){
  print("\n<p>" . $ex->getMessage() . "</p>\n");
}


include "footer.php";

?>
