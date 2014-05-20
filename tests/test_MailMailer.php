<?php
include "classes/MailMessage.php";
include "classes/MailMailer.php";

class ProjectRequest{};
class UserProfile {};
class Operator {};

class test_MailMailer extends PHPUnit_Framework_TestCase {

    public function test_Mail() {
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
            new ProjectRequest, new UserProfile, new Operator,
            $mail_message
        );
        $this->assertEquals('recipient1@localhost, recipient2@localhost', $mail_message->get_recipient());

        $mail_message = new MailMessage();
        $mailer = new MailMailer();

        $mailer->set_override(TRUE);

        $mailer->send_mail("test_template",
            array("recipient1@localhost","recipient2@localhost"),
            new ProjectRequest, new UserProfile, new Operator,
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
