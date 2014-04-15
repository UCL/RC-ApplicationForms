<?

class UserProfile {

    // To match db schema
    private $id = FALSE;
    private $username;
    private $user_upi;
    private $user_type_id;
    private $user_email;
    private $user_contact_number;
    private $user_surname;
    private $user_forenames;
    private $user_forename_preferred;
    private $user_dept;
    private $sponsor_username;
    private $experience_level_id;
    private $experience_text;
    // End of db fields

    protected $actor;
    private $valid = FALSE;

    public function __construct($actor=NULL) {
        if ($actor != NULL) {
            $this->actor = new SQLActor();
            $this->actor->connect();
        } else {
            $this->actor = $actor;
        }
    }

    public static function from_request($request_array) {
        $instance = new self();
        $instance->fill_from_request_array($request_array);
        return $instance;
    }

    public function fill_from_request_array($request_array) {
        foreach ($this as $key => $value) {
            if (array_key_exists($key, $request_array)) {
                $this[$key] = $request_array[$key];
            }
        }

        foreach ($this as $key => $value) {
            if (! isset($this[$key])) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public static function from_db($id) {
        $instance = new self();
        $request_array = $instance->actor->get_user_profile($id);
        $instance->fill_from_request_array($request_array);
        return $instance;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

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

    public function set_user_dept($user_dept) {
        $this->user_dept = $user_dept;
    }

    public function get_user_dept() {
        return $this->user_dept;
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

    public function is_valid() {
        return $this->valid;
    }

    public function can_self_approve() {
        
    }

    public function is_already_stored() {

    }

    public function can_be_altered_by(Operator $an_operator) {
        if ( ($this->get_username() == $an_operator->username() )  ||
             ($an_operator->is_superuser()))
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function save_to_db(Operator $altering_operator) {
        if ($this->can_be_altered_by($altering_operator)) {
            //TODO

        } else {
            return FALSE;
        }
    }

};    

