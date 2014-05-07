<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-05-06
 * Time: 17:32
 */

class Publication {
    // db fields
    private $id;
    private $user_profile_id;
    private $url;
    private $notable = FALSE; //Checkbox default
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

    public static function from_array($an_array) {
        $instance = new self();
        $instance->fill_from_array($an_array);
        $instance->dirty();
        return $instance;
    }

    public static function from_db($publication_id) {
        $instance = new self();
        $an_array = $instance->actor->get_publication($publication_id);
        $instance->fill_from_array($an_array);
        $instance->clean();
        return $instance;
    }

    public function fill_from_array($an_array) {
        foreach ($this as $key => $value) {
            if (array_key_exists($key, $an_array)) {
                $this->$key = $an_array[$key];
            }
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
        $this->set_user_profile_id(UserProfile::from_db_by_name($username)->get_id());
    }

    public function set_owner($username) {
        $this->set_user_profile_id_from_username($username);
    }

    public function save_to_db(Operator $altering_operator) {
        if ($this->user_profile_id === NULL) {
            $this->set_owner($altering_operator->username());
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