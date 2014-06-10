<?php
include_once "includes/autoload_definition.php";

// Note: Mailcatcher may be something to look into here

class test_FullPath extends PHPUnit_Framework_TestCase {

    public function test_FullPRPath() {
        $_SERVER['REQUEST_METHOD'] = "POST";
        $_SERVER['PHP_AUTH_USER'] = "uccasgu";
        $_SERVER['HTTP_REFERER']  = "apply.php";
        $_POST = array(
            "user_profile" => array(
                "username" => "uccaiki",
                "user_upi" => "IKIRK33",
                "user_surname" => "Kirker",
                "user_forenames" => "Ian",
                "user_forename_preferred" => "Ian",
                "user_email" => "i.kirker@ucl.ac.uk",
                "user_dept" => "Chemistry",
                "sponsor_username" => "suprvis",
                "experience_level_id" => 3,
                "experience_text" => "I HAVE 500 XP",
            ),
            "project"      => array(
                "is_funded" => 1,
                "pi_email"  => "i.kirker@ucl.ac.uk",
                "research_theme_id" => 3,
                "work_type_small_mpi" => "on",
                "weird_tech_description" => "",
                "work_description" => "I am do work",
                "applications_description" => "Gaussian 51",
            ),
        );

        $super_user_operator = new Operator("suprusr");
        $supervisor_operator = new Operator("suprvis");
        $normal_operator = new Operator("someguy");
        //$student_operator = new Operator(); // TODO: Find a student

        $pr = ProjectRequest::from_request($_POST);
        $pr->save_to_db($super_user_operator);
        $up = $pr->get_user_profile();

        $this->assertEquals($up->get_username(), "uccaiki");
        $this->assertEquals($up->get_sponsor_username(), "suprvis");
        $this->assertEquals($up->get_experience_text(), "I HAVE 500 XP");

        $this->assertEquals($pr->can_be_altered_by($super_user_operator), TRUE);
        $this->assertEquals($pr->can_be_altered_by($supervisor_operator), FALSE);
        $this->assertEquals($pr->can_be_altered_by($normal_operator), FALSE);

        $this->assertEquals($pr->can_be_approved_by($super_user_operator), TRUE);
        $this->assertEquals($pr->can_be_approved_by($supervisor_operator), TRUE);
        $this->assertEquals($pr->can_be_approved_by($normal_operator), FALSE);

        $pr->update_status($super_user_operator, 'submitted', '(from within a test)');

        $this->assertEquals($pr->get_last_status()->get_text(), "submitted");
        $this->assertEquals($pr->get_last_status()->get_comment(), "(from within a test)");

        $pr->approve_by($supervisor_operator, "(also from within a test)");
        $this->assertEquals($pr->get_last_status()->get_text(), "approved");
        $this->assertEquals($pr->get_last_status()->get_comment(), "(also from within a test)");
    }

}
