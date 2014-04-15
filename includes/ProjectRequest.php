<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-04-10
 * Time: 16:14
 */

include_once "includes/SQLActor.php";
include_once "includes/UserProfile.php";

class ProjectRequest {

    // To match db schema
    private $id = FALSE;
    private $username;
    private $user_profile_id;
    private $research_theme_id;
    private $is_funded;
    private $pi_email;
    private $pi_username;
    private $weird_tech_description;
    private $work_description;
    private $applications_description;
    private $work_required_collated;
    private $collab_bristol_name;
    private $collab_oxford_name;
    private $collab_soton_name;
    private $collab_other_institute;
    private $collab_other_name;
    private $collaboration_collated;

    // Form checkbox matching section
    private $work_type_basic = FALSE;
    private $work_type_array = FALSE;
    private $work_type_multithread = FALSE;
    private $work_type_all_the_ram = FALSE;
    private $work_type_small_mpi = FALSE;
    private $work_type_mid_mpi = FALSE;
    private $work_type_large_mpi = FALSE;
    private $work_type_small_gpu = FALSE;
    private $work_type_large_gpu = FALSE;
    private $is_collab_bristol = FALSE;
    private $is_collab_oxford = FALSE;
    private $is_collab_soton = FALSE;
    private $is_collab_other = FALSE;
    // End of db fields

    /** @var SQLActor actor */
    protected $actor;

    /** @var UserProfile user_profile */
    private $user_profile = NULL;

    /** @var bool valid */
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

    public static function from_db($request_id) {
        $instance = new self();
        $request_array = $instance->actor->get_project_request($request_id);
        $instance->fill_from_request_array($request_array);
        return $instance;
    }

    public function fill_from_request_array($request_array) {

    }

    public function is_valid() {
        return $this->valid;
    }

    public function add_to_db() {
        $this->actor-> // TODO There is not a project request creator yet
    }

    public function get_user_profile() {
        if ($this->user_profile == NULL) {
            $this->user_profile = UserProfile::from_db($this->username);
        }
        return $this->user_profile;
    }

    public function can_be_approved_by(Operator $an_operator) {
        if ($an_operator->is_superuser()) {
            return TRUE;
        }

        if ($this->get_user_profile()->get_sponsor_username() == $an_operator->username()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function can_be_viewed_by(Operator $an_operator) {
        return TRUE; // I don't care who looks at account requests yaaaaaay
    }

    public function approve_by (Operator $an_operator, $comments="") {
        if (! $this->can_be_approved_by($an_operator)) {
            die("Permissions error.\n");
        } else {
            return $this->actor->mark_request_status(
                $this->id,
                $an_operator->username(),
                "approved",
                $comments
            );
        }
    }

    public function reject_by (Operator $operator, $comments="") {
        if (! $this->can_be_approved_by($operator)) {
            die("Permissions error.\n");
        } else {
            return $this->actor->mark_request_status(
                $this->id,
                $operator->username(),
                "rejected",
                $comments
            );
        }
    }

}
