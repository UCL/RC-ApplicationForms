<?php

require_once("includes/MailMessage.php");

class MailMailer {

    static private $override_mail = TRUE;
    static private $override_mail_address = "i.kirker@ucl.ac.uk";
    
    private $globals = array(
        'BASE_URL'   => "http://avon.rc.ucl.ac.uk/acct",
        'TEST_VALUE' => "Success"
    );


    public function get_template($template_name) {
        // Template replacement markers should be delimited with {:name}

        // This is a bit dumb at the moment - might put templates in the db or else (*crazy*) in the mediawiki with a ?skin=raw setting to just return the content of a page, so they can be edited like wiki documents. That would be fun.
        $template_dir = "templates";
        $templates = array(
            "new_account_for_approval" => array (
                'subject' => "RCPS: New Account Request - {:user_forenames} {:user_surname}",
                'body'    => file_get_contents("$template_dir/new_account_for_approval.txt")
            ),
            "new_account_request_approved" => array (
                'subject' => "RCPS: Request Approved",
                'body'    => file_get_contents("$template_dir/new_account_request_approved.txt")
            ),
            "rcps_notify_request_approved" => array (
                'subject' => "AppForm Req Notify: {:user_username} approved in {:consortium}",
                'body'    => file_get_contents("$template_dir/rcps_notify_request_approved.txt"),
                'override_replyto' => "{:acting_user_address}"
            ),
            "rcps_notify_request_rejected" => array (
                'subject' => "AppForm Req Notify: {:user_username} rejected by {:acting_user}",
                'body'    => file_get_contents("$template_dir/rcps_notify_request_rejected.txt"),
                'override_replyto' => "{:acting_user_address}"
            ),
            "test_template" => array (
                'subject' => "Test Message",
                'body'    => file_get_contents("$template_dir/test_template.txt"),
                'override_replyto' => "test-template@localhost"
            )
        );

        return $templates[$template_name];

    }

    public function template_part_process($template_string, $info) {
        // This regex should match up to 2 levels of . indexing into an array
        $replacements = array(':' => $info, '%' => $this->globals);
        $output = preg_replace_callback("/\{(:|%)([^\s.]+?)(?:\.([^\s.]+?)|)(?:\.([^\s]+?)|)\}/",
            function($m) use ($replacements) {
                if (array_key_exists (3, $m)) {
                    if (array_key_exists (4, $m)) {
                        return $replacements[$m[1]][$m[2]][$m[3]][$m[4]];
                    } else {
                        return $replacements[$m[1]][$m[2]][$m[3]];
                    }
                } else {
                    return $replacements[$m[1]][$m[2]];
                }
            },
            $template_string
        );

        return $output;
    }

    public function send_mail($template_name, $addresses_to_mail, $info, $message_object=NULL) {
        if (is_string($addresses_to_mail)) {
            $addresses_to_mail = array($addresses_to_mail);
        }
        if (MailMailer::$override_mail) {
            $stored_addresses = $addresses_to_mail;
            $info['stored_addresses_imploded'] = implode("&x10;",$stored_addresses);
            $addresses_to_mail = array(MailMailer::$override_mail_address);
        }
        
        $template = $this->get_template($template_name);

        $to      = implode(", ", $addresses_to_mail);
        $subject = $this->template_part_process($template['subject'],$info);
        $body    = $this->template_part_process($template['body'],$info);
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: Research Computing Support <rc-support@ucl.ac.uk> \r\n";
        if (array_key_exists('override_replyto', $template)) {
            $headers .= "Reply-to: " . $this->template_part_process($template['override_replyto'], $info) . " \r\n";
        } else {
            $headers .= "Reply-to: Research Computing Support <rc-support@ucl.ac.uk> \r\n";
        }

        // Provide your own MailMessage object for, e.g., unit testing.
        if ($message_object == NULL) {
            $message_object = new MailMessage();
        }
        $message_object->set_all($to, $subject, $body, $headers);
        $result = $message_object->send();

        return $result;
    }

}

?>
