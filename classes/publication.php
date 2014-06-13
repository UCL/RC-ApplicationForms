<?php

include_once "includes/config.php";

/**
 * The Publication class handles the storage of information about user publications, shockingly.
 *
 * It represents either a current or future entry in the Publications table of the database.
 */
class Publication {
    // db fields
    private $id;
    private $user_profile_id;
    private $url;
    private $notable = FALSE; //Checkbox default
    private $time_added;
    // end of db fields

    /** @var SQLActor actor */
    protected $actor;

    /** @var bool is_clean */
    private $is_clean; /* i.e. is this consistent with the database contents */

    public function __construct($actor=NULL) {
        $this->id = FALSE;
        $this->user_profile_id = NULL;

        if ($actor == NULL) {
            $this->actor = new SQLActor();
            $this->actor->connect();
        } else {
            $this->actor = $actor;
        }
        $this->dirty();
    }

    public static function from_array($an_array, $actor=NULL) {
        $instance = new self($actor);
        $instance->fill_from_array($an_array);
        $instance->dirty();
        return $instance;
    }

    public static function from_db($publication_id, $actor=NULL) {
        $instance = new self($actor);
        $an_array = $instance->actor->get_publication($publication_id);
        $instance->fill_from_array($an_array);
        $instance->clean();
        return $instance;
    }

    public function fill_from_array($an_array) {
        if (array_key_exists('id',$an_array)) {
            $this->user_profile_id = $an_array['id'];
        }
        if (array_key_exists('user_profile_id',$an_array)) {
            $this->user_profile_id = $an_array['user_profile_id'];
        }
        if (array_key_exists('notable',$an_array)) {
            if ($an_array['notable'] == "on") {
                $this->notable = 1;
            } else {
                $this->notable = $an_array['notable'];
            }
        } else {
            $this->notable = 0;
        }
        if (array_key_exists('url',$an_array)) {
            $this->url = $an_array['url'];
        } else {
            die("Error: no url provided to publication.");
        }
        if (array_key_exists('time_added',$an_array)) {
            $this->time_added = $an_array['time_added'];
        }
    }

    public function get_url() {
        return $this->url;
    }

    public function set_user_profile_id($id) {
        $this->user_profile_id = $id;
    }

    public function get_user_profile_id() {
        return $this->user_profile_id;
    }

    public function set_user_profile_id_from_username($username) {
        $user_profile = UserProfile::from_db_by_name($username, $this->actor);
        $this->set_user_profile_id($user_profile->get_id());
    }

    public function set_owner($username) {
        $this->set_user_profile_id_from_username($username);
    }

    public function save_to_db(Operator $altering_operator) {
        if ($this->user_profile_id === NULL) {
            $this->set_owner($altering_operator->get_username());
        }
        $created_id = $this->actor->save_publication($this->get_packed_data());
        $this->set_id($created_id);
        if ($created_id != FALSE) {
            $this->clean();
            return $created_id;
        } else {
            return FALSE;
        }
    }

    public function get_packed_data() {
        $data_array = array();
        if ($this->id !== FALSE) {
            $data_array['id'] = $this->id;
        }
        $data_array['user_profile_id'] = $this->user_profile_id;
        $data_array['url'] = $this->url;
        $data_array['notable'] = $this->notable;
        return $data_array;
    }

    public function has_valid_url() {
        $headers = @get_headers($this->url);
        if(strpos($headers[0],'200')===FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function is_notable() {
        return $this->notable;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function clean() {
        $this->is_clean = TRUE;
    }

    public function dirty() {
        $this->is_clean = FALSE;
    }
}
