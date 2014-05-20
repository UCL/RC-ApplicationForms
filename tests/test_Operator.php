<?php

include_once "classes/Operator.php";
include_once "classes/SQLActor.php";

class OperatorMockSQLActor {
    public function get_user_info($username) {
        if ($username == "not_appearing_in_this_db") {
            return FALSE;
        } elseif ($username == "no_special_permissions") {
            return array("id" => 1,
                         "username" => $username,
                         "fullname" => "(the name of a user with no special permissions)",
                         "super_special_rainbow_pegasus_powers" => FALSE,
                         "receives_emails" => TRUE,
                         "email_address" => $username . "@localhost"
                     );
        } elseif ($username == "superuser") {
            return array("id" => 2,
                         "username" => $username,
                         "fullname" => "(the name of a superuser)",
                         "super_special_rainbow_pegasus_powers" => TRUE,
                         "receives_emails" => TRUE,
                         "email_address" => $username . "@localhost"
                     );
        }
    }
}

class test_Operator extends PHPUnit_Framework_TestCase {

    public function test_DudUser() {
        $mock_sql_actor = $this->getMockBuilder("SQLActor")->getMock();
        $mock_sql_actor->expects($this->once())->method('get_user_info')
            ->will($this->returnValue(FALSE));
        $test_operator = new Operator("not_appearing_in_this_db", $mock_sql_actor);
        $this->assertEquals($test_operator->is_superuser(), FALSE);
        $this->assertEquals($test_operator->get_username(), "not_appearing_in_this_db");
        $this->assertEquals($test_operator->get_full_name(), "(user full name not in permissions db)"); // This is the default
        $this->assertEquals($test_operator->get_email_address(), "");
    }

    public function test_NormalUser() {
        $normal_user_info = array("id" => 1,
                     "username" => "no_special_permissions",
                     "full_name" => "(the name of a user with no special permissions)",
                     "super_special_rainbow_pegasus_powers" => FALSE,
                     "receives_emails" => TRUE,
                     "email_address" => "no_special_permissions@localhost"
                 );
        $mock_sql_actor = $this->getMockBuilder("SQLActor")->getMock();
        $mock_sql_actor->expects($this->once())->method('get_user_info')
            ->will($this->returnValue($normal_user_info));
        $test_operator = new Operator("a_user_name", $mock_sql_actor);
        $this->assertEquals($test_operator->is_superuser(), FALSE);
        $this->assertEquals($test_operator->get_username(), "no_special_permissions");
        $this->assertEquals($test_operator->get_full_name(), "(the name of a user with no special permissions)");
        $this->assertEquals($test_operator->get_email_address(), "no_special_permissions@localhost");
    }

    public function test_SuperUser() {
        $superuser_info = array("id" => 2,
                     "username" => "superuser",
                     "full_name" => "(the name of a superuser)",
                     "super_special_rainbow_pegasus_powers" => TRUE,
                     "receives_emails" => TRUE,
                     "email_address" => "superuser@localhost"
                 );
        $mock_sql_actor = $this->getMockBuilder("SQLActor")->getMock();
        $mock_sql_actor->expects($this->once())->method('get_user_info')
            ->will($this->returnValue($superuser_info));
        $test_operator = new Operator("a_super_user_name", $mock_sql_actor);
        var_dump($test_operator);
        $this->assertEquals($test_operator->is_superuser(), TRUE);
        $this->assertEquals($test_operator->get_username(), "superuser");
        $this->assertEquals($test_operator->get_full_name(), "(the name of a superuser)");
        $this->assertEquals($test_operator->get_email_address(), "superuser@localhost");
    }
}

?>
