<?php

include_once "Operator.php";

class RequestPair {
    private $user_profile_id;
    private $project_id;
    private $account_request;
    private $project;
    private $actor;
    private $valid;

    public function __construct($con_project_id) {
        if (is_null($con_project_id)) {
            // Shortcut
            $this->valid=FALSE;
            return;
        }

        $this->actor=new SQLActor();
        $this->actor->connect();
        $this->project_id = $con_project_id;

        $request_pair = $this->actor->get_request_pair_from_project_id($con_project_id);

        if ($request_pair == FALSE) {
            $this->valid=FALSE;
            return;
        } else {
            $this->account_request = $request_pair[0];
            $this->project = $request_pair[1];
            $this->user_profile_id = $this->account_request['id']; // Hack until further classified
            $this->valid=TRUE;
            return;
        }
    }

    public function is_valid() {
        return $this->valid;
    }

    public function can_be_approved_by (Operator $operator) {
        if ($operator->is_superuser() ||
            $operator->is_leader_for($this->project['consortium_id']) )
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }





    public function owner() {
        return $this->project['username'];
    }

    public function user_email() {
        return $this->account_request['user_email'];
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

    public function last_status_comments() {
        return $this->actor->get_last_status_comments($this->project_id);
    }

    public function get_approval_anchor() {
        return "<a href=\"".
        "view.php?" .
        "&idp=" . $this->project_id .
        "&action=approve".
        "\">Approve this Request</a>\n";
    }

    public function get_view_anchor() {
        return "<a href=\"".
        "view.php?" .
        "&idp=" . $this->project_id .
        "\">View this Request</a>\n";
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
            $this->get_approval_anchor()
        );
        return $html;
    }

    public function as_table() {
        $html =
            "<table class='silvatable grid'><tr class='odd'><td colspan=2><strong>User</strong></td></tr>"
            // TODO: If you ever get around to class-ifying Project and Account_Request, this should call those instead. :/
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

?>
