<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-05-08
 * Time: 16:17
 */

class ProjectRequestStatus {
    private $id;
    private $project_request_id;
    private $status_type_id;
    private $acting_user;
    private $comment;
    private $update_time;

    /** @var SQLActor actor */
    protected $actor;

    public function __construct($actor=NULL) {
        if ($actor == NULL) {
            $this->actor = new SQLActor();
            $this->actor->connect();
        } else {
            $this->actor = $actor;
        }
    }

    public static function from_db($request_id) {
        $instance = new self();
        $project_request_status_array = $instance->actor->get_last_project_request_status($request_id);
        $instance->fill_from_array($project_request_status_array);
        return $instance;
    }

    public function fill_from_array($project_request_status_array) {
        foreach (array_keys($project_request_status_array) as $key) {
            $this->$key = $project_request_status_array[$key];
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function get_text() {
        return $this->actor->get_status_type($this->status_type_id);
    }

    public function get_acting_user() {
        return $this->acting_user;
    }

    public function get_comment() {
        return $this->comment;
    }

    public function get_update_time() {
        return $this->update_time;
    }

}
