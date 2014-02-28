<?php

$page_title = "View Account Request";
include "header.php";
include "sqlactor.php";

function table_keyval($label, $value, $columns=2) {
    if ($columns == 2) {
        $html  = "";
        $html .= "    <tr class='even'>\n";
        $html .= "        <td>\n";
        $html .= "            <strong>{$label}</strong>\n";
        $html .= "        </td>\n";
        $html .= "        <td>\n";
        $html .= "            {$value}\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
    } elseif ($columns == 1) {
        $html  = "";
        $html .= "    <tr>\n";
        $html .= "        <td colspan=2 class='odd'>\n";
        $html .= "            <strong>{$label}</strong>\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "        <td colspan=2 class='even'>\n";
        $html .= "            {$value}\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
    }
    return $html;
}

class RequestPair {
    private $account_request_id;
    private $project_id;
    private $account_request;
    private $project;
    private $actor;
    private $valid;

    public function __construct($con_account_request_id, $con_project_id) {
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

    public function approve_by ($user) {
        if (! $this->can_be_approved_by($user)) {
            die("Permissions error.\n");
        } else {
            echo "HERPADERPA";
            return $this->actor->approve_request($this->account_request_id, $this->project_id, $user->username());
        }
    }

    public function owner() {
        return $this->project['username'];
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
         . table_keyval("Project Description", $this->project['work_description'], 1)
         . table_keyval("Applications Required", $this->project['applications_description'], 1)
         . table_keyval("Work Required", $this->project['work_required_collated'], 1)
         . table_keyval("Unusual Technical Requirements", $this->project['weird_tech_description'], 1)
         . table_keyval("Collaboration (if any)", $this->project['collaboration_collated'], 1)
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


//$current_username = $_SERVER['PHP_AUTH_USER'];
$current_username = "uccaiki";

$req_account_request_id = $_GET['ida'];
$req_project_id         = $_GET['idp'];
$req_action             = $_GET["action"];

try{
    $actor = new SQLActor();
    $current_user = new User($current_username);

    $request_pair = new RequestPair($req_account_request_id, $req_project_id);
    if ($request_pair->is_valid() == FALSE) {
        echo "<h4>Invalid Entry Requested. If you believe this is a mistake, please contact rc-support@ucl.ac.uk, pasting into the email the full address of this page.</h4>";
    } else {

        if ($request_pair->can_be_approved_by($current_user)) {
            if ($req_action == "approve") {
                $result = $request_pair->approve_by($current_user);
                if ($result == TRUE) {
                    $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                    "Request Approved".
                                    "</div>";
                } elseif ($result == FALSE) {
                    $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                    "There was an error approving the request. ".
                                    "Please contact rc-support@ucl.ac.uk.".
                                    "</div>";
                }
            }
            
            if ($request_pair->last_status_text() == "submitted") { 
                $approval_div = "<div width=\"100%\" style='text-align:center; background-color: #FCE7A1;'>" .
                                $request_pair->get_approval_link() .
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
            case "expired":
                echo "<p class='p'>This project expired on: ".$request_pair->last_status_time()."</p>";
                break;
            case "broken":
                echo "<p class='p'>This request was marked as broken on: ".$request_pair->last_status_time()."</p>";
                break;
            default:
                echo "<h4>This request is in an unexpected state: {$request_pair->last_status_text()}. Please contact rc-support@ucl.ac.uk and let them know.</h4>";
        }

        echo $approval_div;

        echo $request_pair->as_table();
    }

} catch(\PDOException $ex){
  print("\n<p>" . $ex->getMessage() . "</p>\n");
}


include "footer.php";

?>
