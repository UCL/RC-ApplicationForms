<?

class User {
    private username;
    private user_upi;
    private user_type_id;
    private user_email;
    private user_contact_number;
    private user_surname;
    private user_forenames;
    private user_forename_preferred;
    private user_dept;
    private sponsor_username;
    private experience_level_id;
    private experience_text;

    public function set_username($name) {
        $this->username = $name;
    }

    public function get_username() { 
        return $this->username;
    }

    public function set_user_upi($upi) {
        $this->user_upi = $upi;
    }

    public function get_user_upi() { 
        return $this->user_upi; 
    }

    public function set_user_type_id($type_id) {
        $this->user_type_id = $type_id;
    }

    public function get_user_type_id() { 
        return $this->user_type_id; 
    }

    public function set_user_email($email) {
        $this->user_email = $email;
    }

    public function get_user_email() { 
        return $this->user_email; 
    }

    public function set_user_contact_number($number) {
        $this->user_contact_number = $number;
    }

    public function get_user_contact_number() { 
        return $this->user_contact_number; 
    }

    public function set_user_surname($surname) {
        $this->user_surname = $surname;
    }

    public function get_user_surname() { 
        return $this->user_surname; 
    }

    public function set_user_forenames($forenames) {
        $this->user_forenames = $forenames;
    }

    public function get_user_forenames() { 
        return $this->user_forenames; 
    }

    public function set_user_forename_preferred($forename) {
        $this->user_forename_preferred = $forename;
    }

    public function get_user_forename_preferred() {
        return $this->user_forename_preferred;
    }

    public function set_sponsor_username($name) {
        $this->sponsor_username = $name;
    }

    public function get_sponsor_username() {
        return $this->sponsor_username;
    }

    public function set_experience_level_id($id) {
        $this->experience_level_id = $id;
    }

    public function get_experience_level_id() {
        return $this->experience_level_id;
    }

    public function set_experience_text($text) {
        $this->experience_text = $text;
    }

    public function get_experience_text() {
        return $this->experience_text;
    }

    public function can_self_approve() {
        
    }

    public function is_already_stored() {

    }

    public function save_to_db() {

    }

};    

