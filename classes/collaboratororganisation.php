<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-06-27
 * Time: 11:48
 */

include_once "includes/config.php";

/**
 * represents either a current or future entry in the Collaborator_Organisations table of the database.
 */
class Collaborator_Organisation {
    // db fields
    private $id;
    private $project_request_id;
    private $is_private_sector;
    private $organisation_name;
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
        $an_array = $instance->actor->get_collaborator_organisation($publication_id);
        $instance->fill_from_array($an_array);
        $instance->clean();
        return $instance;
    }

    public function fill_from_array($an_array) {
        if (array_key_exists('id',$an_array)) {
            $this->id = $an_array['id'];
        }
        if (array_key_exists('project_request_id',$an_array)) {
            $this->project_request_id = $an_array['project_request_id'];
        }
        if (array_key_exists('is_private_sector',$an_array)) {
            if ($an_array['is_private_sector'] == "on") {
                $this->is_private_sector = 1;
            } else {
                $this->is_private_sector = $an_array['is_private_sector'];
            }
        } else {
            $this->is_private_sector = 0;
        }
        if (array_key_exists('organisation_name',$an_array)) {
            $this->organisation_name = $an_array['organisation_name'];
        } else {
            die("Error: no name provided for organisation.");
        }
        if (array_key_exists('time_added',$an_array)) {
            $this->time_added = $an_array['time_added'];
        }
    }

    public function get_organisation_name() {
        return $this->organisation_name;
    }

    public function set_project_request_id($id) {
        $this->project_request_id = $id;
    }

    public function get_project_request_id() {
        return $this->project_request_id;
    }

    public function save_to_db(Operator $altering_operator) {
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
        $data_array['project_request_id'] = $this->project_request_id;
        $data_array['organisation_name'] = $this->organisation_name;
        $data_array['is_private_sector'] = $this->is_private_sector;
        return $data_array;
    }

    public function private_sector() {
        return $this->is_private_sector;
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
