<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-04-10
 * Time: 16:14
 */

// not sure whether defining default (i.e. on construction) values like I have here is good practice/a good idea


class ProjectRequest {

    // To match db schema
    private $id = FALSE;
    private $user_profile_id = FALSE;
    private $research_theme_id;
    private $is_funded;
    private $pi_email;
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

    /** @var bool is_clean */
    private $is_clean; /* i.e. is this consistent with the database contents */


    public function __construct($actor=NULL) {
        if ($actor == NULL) {
            $this->actor = new SQLActor();
            $this->actor->connect();
        } else {
            $this->actor = $actor;
        }
        $this->clean();
    }

    public static function from_request($request_array) {
        $instance = new self();
        $instance->fill_from_array($request_array['project']);

        if ($instance->get_user_profile_id() === FALSE) {
            // Then we need to create a user profile object from the request too
            $user_profile = UserProfile::from_request($request_array['user_profile']);

            $instance->bind_user_profile($user_profile);
        }
        $instance->dirty();
        return $instance;
    }

    public static function from_db($request_id) {
        $instance = new self();
        $project_array = $instance->actor->get_project_request($request_id);
        $instance->fill_from_array($project_array['project']);
        $instance->clean();
        return $instance;
    }

    public function fill_from_array($project_array) {
        foreach (array_keys($project_array) as $key) {
            $this->$key = $project_array[$key];
        }
    }

    public function is_valid() {
        return $this->valid;
    }

    public function set_user_profile_id($id) {
        $this->user_profile_id = $id;
    }

    public function get_user_profile_id() {
        return $this->user_profile_id;
    }

    public function get_user_profile() {
        if ($this->user_profile == NULL) {
            if ($this->get_user_profile_id() === FALSE) {
                die ("Tried to use user profile from project request without a user profile...");
            } else {
                $this->bind_user_profile(UserProfile::from_db($this->get_user_profile_id()));
            }
        }
        return $this->user_profile;
    }

    public function bind_user_profile(UserProfile $user_profile) {
        $this->user_profile = $user_profile;
        if ($this->get_user_profile()->get_id() !== FALSE) {
            $this->set_user_profile_id($user_profile->get_id());
        }
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

    public function can_be_altered_by(Operator $an_operator) {
        if ($an_operator->is_superuser()) {
            return TRUE;
        }

        if ($this->get_user_profile()->get_username() == $an_operator->username()) {
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

    public function set_id($id) {
        $this->id=$id;
    }

    public function get_id() {
        return $this->id;
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

    public function save_to_db(Operator $altering_operator) {
        if ($this->can_be_altered_by($altering_operator)) {
            if ($this->get_user_profile()->save_to_db($altering_operator) === FALSE) {
                die ("Could not enter user profile information in database.");
            } else {
                if ($this->get_user_profile_id() === FALSE) {
                    $this->set_user_profile_id($this->get_user_profile()->get_id());
                }
                $created_id = $this->actor->save_project_request($this->get_packed_data());
                $this->set_id($created_id);
                $this->clean();
                return TRUE;
            }
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
        $data_array['research_theme_id'] = $this->research_theme_id;
        $data_array['is_funded'] = $this->is_funded;
        $data_array['pi_email'] = $this->pi_email;
        $data_array['weird_tech_description'] = $this->weird_tech_description;
        $data_array['work_description'] = $this->work_description;
        $data_array['applications_description'] = $this->applications_description;
        $data_array['collab_bristol_name'] = $this->collab_bristol_name;
        $data_array['collab_oxford_name'] = $this->collab_oxford_name;
        $data_array['collab_soton_name'] = $this->collab_soton_name;
        $data_array['collab_other_institute'] = $this->collab_other_institute;
        $data_array['collab_other_name'] = $this->collab_other_name;

        //checkbox matching section
        $data_array['work_type_basic'] = (int)(bool) $this->work_type_basic;
        $data_array['work_type_array'] = (int)(bool) $this->work_type_array;
        $data_array['work_type_multithread'] = (int)(bool) $this->work_type_multithread;
        $data_array['work_type_all_the_ram'] = (int)(bool) $this->work_type_all_the_ram;
        $data_array['work_type_small_mpi'] = (int)(bool) $this->work_type_small_mpi;
        $data_array['work_type_mid_mpi'] = (int)(bool) $this->work_type_mid_mpi;
        $data_array['work_type_large_mpi'] = (int)(bool) $this->work_type_large_mpi;
        $data_array['work_type_small_gpu'] = (int)(bool) $this->work_type_small_gpu;
        $data_array['work_type_large_gpu'] = (int)(bool) $this->work_type_large_gpu;
        $data_array['is_collab_bristol'] = (int)(bool) $this->is_collab_bristol;
        $data_array['is_collab_oxford'] = (int)(bool) $this->is_collab_oxford;
        $data_array['is_collab_soton'] = (int)(bool) $this->is_collab_soton;
        $data_array['is_collab_other'] = (int)(bool) $this->is_collab_other;
        return $data_array;
    }

    public function update_status (Operator $operator, $status_text, $comments="") {
        if ($this->id === FALSE) {
            die("Cannot update status for an unsaved ProjectRequest. (If you see this message and are not the programmer, something is broken.)");
        }
        return $this->actor->mark_request_status($this->id,
                                                 $operator->username(),
                                                 $status_text,
                                                 $comments);
    }

}
