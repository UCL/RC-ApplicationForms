<?php

include_once "sqlactor.php";

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

    public function full_name() {
        return $this->full_name;
    }

    public function email_address() {
        return $this->email_address;
    }
}

?>