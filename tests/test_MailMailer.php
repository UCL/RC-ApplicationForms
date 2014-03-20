<?php

include_once "includes/MailMailer.php";
include_once "includes/MailMessage.php";

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

        $mailer->send_mail("test_template", 
            array("recipient1@localhost","recipient2@localhost"), 
            array("basic_replacement" => "Success 1",
                  "one_deep" => array( "replacement" => "Success 2" ),
                  "two" => array( "deep" => array( "replacement" => "Success 3" ) ) ),
            $mail_message
        );

        $this->assertEquals('recipient1@localhost, recipient2@localhost', $mail_message->get_recipient());
        $this->assertEquals('Test Message', $mail_message->get_subject());
        $this->assertEquals($mail_message->get_message(),
                            "Line with no replacement.\n" .
                            "Basic Replacement: Success 1\n" .
                            "One-deep Replacement: Success 2\n" .
                            "Two-deep Replacement: Success 3\n" .
                            "\n" .
                            "^ Blank line.\n");
        $this->assertEquals($mail_message->get_headers(),
                            "MIME-Version: 1.0" . "\r\n" .
                            "From: Research Computing Support <rc-support@ucl.ac.uk> \r\n" .
                            "Reply-to: test-template@localhost \r\n");

    }
}

?>
