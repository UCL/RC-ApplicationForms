<?php

include_once "includes/config.php";

/**
 * The UserProfile class handles information about one user who has submitted an application.
 *
 * It represents a current or future entry in the User_Profiles table in the database, and is
 *  used either alone or as a member of a ProjectRequest object.
 */
class UserProfile {

    // To match db schema
    private $id;
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
    private $creation_time;
    // End of db fields

    /** @var SQLActor actor */
    protected $actor;

    /** @var bool is_clean */
    private $is_clean; /* i.e. is this consistent with the database contents */

    public function __construct($actor=NULL) {
        $this->id = FALSE;

        if ($actor == NULL) {
            $this->actor = new SQLActor();
            $this->actor->connect();
        } else {
            $this->actor = $actor;
        }
        $this->dirty();
    }

    public static function from_request($request_array, $actor=NULL) {
        $instance = new self($actor);
        $instance->fill_from_request_array($request_array);
        $instance->dirty(); // Because it hasn't been saved to the db yet
        return $instance;
    }

    public static function from_db_set($request_array, $actor=NULL) {
        $instance = new self($actor);
        $instance->fill_from_request_array($request_array);
        return $instance;
    }

    public static function get_all_from_db($actor = NULL) {
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }

        $profile_arrays = $actor->get_all_user_profiles();
        $profiles = array();
        foreach ($profile_arrays as $one_profile_array) {
            array_push($profiles,UserProfile::from_db_set($one_profile_array, $actor));
        }
        return $profiles;
    }

    public static function get_all_unique_from_db($actor = NULL) {
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }
        $profile_arrays = $actor->get_all_unique_user_profiles();
        $profiles = array();
        foreach ($profile_arrays as $one_profile_array) {
            array_push($profiles,UserProfile::from_request($one_profile_array, $actor));
        }
        return $profiles;
    }

    public static function get_all_for_one_username($username, $actor = NULL) {
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }
        $profile_arrays = $actor->get_all_profiles_for_one_username($username);
        $profiles = array();
        foreach ($profile_arrays as $one_profile_array) {
            array_push($profiles,UserProfile::from_request($one_profile_array, $actor));
        }
        return $profiles;
    }

    public static function from_db($user_profile_id, $actor=NULL) {
        $instance = new self($actor);
        $request_array = $instance->actor->get_user_profile($user_profile_id);
        if ($request_array == FALSE) {
            return FALSE;
        } else {
            $instance->fill_from_request_array($request_array);
            return $instance;
        }
    }

    public static function from_db_by_name($username, $actor=NULL) {
        // NB: This gets their *last* profile in the db sorted by id
        $instance = new self($actor);
        $request_array = $instance->actor->get_user_profile_by_name($username);

        if ($request_array === FALSE) {
            return FALSE;
        } else {
            $instance->fill_from_request_array($request_array);
            return $instance;
        }
    }

    public function fill_from_request_array($request_array) {
        // Populates *any class field* that the passed array contains
        foreach ($this as $key => $value) {
            if (array_key_exists($key, $request_array)) {
                $this->$key = $request_array[$key];
            }
        }
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_username($name) {
        $this->username = $name;
        $this->dirty();
    }

    public function get_username() {
        return $this->username;
    }

    public function set_user_upi($upi) {
        $this->user_upi = $upi;
        $this->dirty();
    }

    public function get_user_upi() {
        return $this->user_upi;
    }

    public function set_user_type_id($type_id) {
        $this->user_type_id = $type_id;
        $this->dirty();
    }

    public function get_user_type_id() {
        return $this->user_type_id;
    }

    public function set_user_email($email) {
        $this->user_email = $email;
        $this->dirty();
    }

    public function get_user_email() {
        return $this->user_email;
    }

    public function set_user_contact_number($number) {
        $this->user_contact_number = $number;
        $this->dirty();
    }

    public function get_user_contact_number() {
        return $this->user_contact_number;
    }

    public function set_user_surname($surname) {
        $this->user_surname = $surname;
        $this->dirty();
    }

    public function get_user_surname() {
        return $this->user_surname;
    }

    public function set_user_forenames($forenames) {
        $this->user_forenames = $forenames;
        $this->dirty();
    }

    public function get_user_forenames() {
        return $this->user_forenames;
    }

    public function set_user_forename_preferred($forename) {
        $this->user_forename_preferred = $forename;
        $this->dirty();
    }

    public function get_user_forename_preferred() {
        return $this->user_forename_preferred;
    }

    public function set_user_dept($user_dept) {
        $this->user_dept = $user_dept;
        $this->dirty();
    }

    public function get_user_dept() {
        return $this->user_dept;
    }

    public function set_sponsor_username($name) {
        $this->sponsor_username = $name;
        $this->dirty();
    }

    public function get_sponsor_username() {
        return $this->sponsor_username;
    }

    public function set_experience_level_id($id) {
        $this->experience_level_id = $id;
        $this->dirty();
    }

    public function get_experience_level_id() {
        return $this->experience_level_id;
    }

    public function get_experience_level() {
        return $this->actor->get_user_experience_level($this->experience_level_id);
    }

    public function set_experience_text($text) {
        $this->experience_text = $text;
        $this->dirty();
    }

    public function get_experience_text() {
        return $this->experience_text;
    }

    public function get_creation_time() {
        return $this->creation_time;
    }

    public function is_clean() {
        return $this->is_clean;
    }

    public function dirty() {
        $this->is_clean = FALSE;
    }

    public function clean() {
        $this->is_clean = TRUE;
    }

    public function can_self_approve() {
        //TODO when we have a way of determining this (from ResourceLink)
    }

    public function get_sponsor_email_address() {
        return $this->get_sponsor_username() . "@ucl.ac.uk";
    }

    public function can_be_altered_by(Operator $an_operator) {
        if ( ($this->get_username() == $an_operator->get_username() )  ||
             ($an_operator->is_superuser()))
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function save_to_db(Operator $altering_operator) {
        if ($this->can_be_altered_by($altering_operator)) {
            $created_id = $this->actor->save_user_profile($this->get_packed_data());
            $this->set_id($created_id);
            $this->clean();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_packed_data() {
        $data_array = array();
        if ($this->id !== FALSE) {
            $data_array['id'] = $this->id;
        }
        $data_array['username'] = $this->username;
        $data_array['user_upi'] = $this->user_upi;
        $data_array['user_type_id'] = $this->user_type_id;
        $data_array['user_email'] = $this->user_email;
        $data_array['user_contact_number'] = $this->user_contact_number;
        $data_array['user_surname'] = $this->user_surname;
        $data_array['user_forenames'] = $this->user_forenames;
        $data_array['user_forename_preferred'] = $this->user_forename_preferred;
        $data_array['user_dept'] = $this->user_dept;
        $data_array['sponsor_username'] = $this->sponsor_username;
        $data_array['experience_level_id'] = $this->experience_level_id;
        $data_array['experience_text'] = $this->experience_text;
        return $data_array;
    }

    public function has_open_project_request() {
        return $this->actor->does_user_have_existing_project_request($this->get_username());
    }

};
