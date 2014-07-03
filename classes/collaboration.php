<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-06-27
 * Time: 11:48
 */

include_once "includes/config.php";

/**
 * represents either a current or future entry in the Collaborations table of the database.
 */
class Collaboration {
    // db fields
    private $id;
    private $project_request_id;
    private $is_private_sector;
    private $organisation_name;
    private $collaborator_contact_name;
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
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }
        $instance = new self($actor);
        $instance->fill_from_array($an_array);
        $instance->dirty();
        return $instance;
    }

    public static function from_db($collaboration_id, $actor=NULL) {
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }
        $instance = new self($actor);
        $an_array = $instance->actor->get_collaboration($collaboration_id);
        $instance->fill_from_array($an_array);
        $instance->clean();
        return $instance;
    }

    public static function get_all_for_project($project_request_id, $actor=NULL) {
        if ($actor == NULL) {
            $actor = new SQLActor();
            $actor->connect();
        }
        $collaboration_arrays = $actor->get_all_collaborations_for_project($project_request_id);
        $collaborations = array();

        foreach ($collaboration_arrays as $one_collaboration_array) {
            array_push($collaborations, Collaboration::from_array($one_collaboration_array, $actor));
        }

        return $collaborations;
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
            $this->organisation_name = "(no organisation provided)";
        }
        if (array_key_exists('collaborator_contact_name',$an_array)) {
            $this->collaborator_contact_name = $an_array['collaborator_contact_name'];
        } else {
            $this->collaborator_contact_name = "(no name provided)";
        }
        if (array_key_exists('time_added',$an_array)) {
            $this->time_added = $an_array['time_added'];
        }
    }

    public function get_organisation_name() {
        return $this->organisation_name;
    }

    public function get_collaborator_contact_name() {
        return $this->collaborator_contact_name;
    }

    public function get_time_added() {
        return $this->time_added;
    }

    public function set_project_request_id($id) {
        $this->project_request_id = $id;
    }

    public function get_project_request_id() {
        return $this->project_request_id;
    }

    public function save_to_db(Operator $altering_operator) {
        $created_id = $this->actor->save_collaboration($this->get_packed_data());
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
        $data_array['collaborator_contact_name'] = $this->collaborator_contact_name;
        $data_array['is_private_sector'] = $this->is_private_sector;
        return $data_array;
    }

    public function get_private_sector_status() {
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
