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
                "user_dept" => "Chemistry",
                "sponsor_username" => "uccaoke",
                "experience_level_id" => 3,
                "experience_text" => "I HAVE 500 XP",
            ),
            "project"      => array(
                "is_funded" => "on",
                "pi_email"  => "i.kirker@ucl.ac.uk",
                "research_theme_id" => 3,
                "work_type_small_mpi" => "on",
                "weird_tech_description" => "",
                "work_description" => "I am do work",
                "applications_description" => "Gaussian 51",
            ),
        );

        $newest_uccaiki = UserProfile::from_request($_POST['user_profile']);

        $newest_uccaiki->save_to_db(new Operator("uccaiki"));
        $newest_uccaiki = UserProfile::from_db_by_name("uccaiki");

        $this->assertEquals($newest_uccaiki->get_username(), "uccaiki");
        $this->assertEquals($newest_uccaiki->get_sponsor_username(), "uccaoke");
        $this->assertEquals($newest_uccaiki->get_experience_text(), "I HAVE 500 XP");
    }

}
