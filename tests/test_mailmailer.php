<?php
include_once "includes/autoload_definition.php";

// Note: Mailcatcher may be something to look into here

class test_MailMailer extends PHPUnit_Framework_TestCase {

    public function test_Mail() {
        $mock_sqa= $this->getMockBuilder("SQLActor")->getMock();
        $mock_pr = $this->getMockBuilder("ProjectRequest")->setConstructorArgs(array($mock_sqa))->getMock();
        $mock_up = $this->getMockBuilder("UserProfile")->setConstructorArgs(array($mock_sqa))->getMock();
        $mock_op = $this->getMockBuilder("Operator")->setConstructorArgs(array("some_guy", $mock_sqa))->getMock();

        $mail_message = new MailMessage();
        $mail_message->set_all('a', 'b', 'c', 'd', 'e');

        $this->assertEquals('a', $mail_message->get_recipient());
        $this->assertEquals('b', $mail_message->get_subject());
        $this->assertEquals('c', $mail_message->get_message());
        $this->assertEquals('d', $mail_message->get_headers());
        $this->assertEquals('e', $mail_message->get_additional_parameters());

        $mail_message = new MailMessage();
        $mailer = new MailMailer();

        $mailer->set_override(FALSE);

        $mailer->send_mail("test_template",
            array("recipient1@localhost","recipient2@localhost"),
            $mock_pr, $mock_up, $mock_op,
            $mail_message
        );
        $this->assertEquals('recipient1@localhost, recipient2@localhost', $mail_message->get_recipient());

        $mail_message = new MailMessage();
        $mailer = new MailMailer();

        $mailer->set_override(TRUE);

        $mailer->send_mail("test_template",
            array("recipient1@localhost","recipient2@localhost"),
            $mock_pr, $mock_up, $mock_op,
            $mail_message
        );
        $this->assertEquals($mailer->get_override_address(), $mail_message->get_recipient());

        $this->assertEquals('Test Email Subject', $mail_message->get_subject());
        $this->assertEquals($mail_message->get_message(),
                            "Test Email Body");
        $this->assertEquals($mail_message->get_headers(),
                            "MIME-Version: 1.0" . "\r\n" .
                            "From: Research Computing Support <rc-support@ucl.ac.uk> \r\n" .
                            "Reply-to: test-template@localhost \r\n");

    }
}

?>
