<?php

class MailMailer {

    static private $override_mail = TRUE;
    static private $override_mail_address = "i.kirker@ucl.ac.uk";

    public function get_template($template_name) {
        // Template replacement markers should be delimited with {:name}

        // This is a bit dumb at the moment - might put templates in the db or else (*crazy*) in the mediawiki with a ?skin=raw setting to just return the content of a page, so they can be edited like wiki documents. That would be fun.
        $templates = array(
            "new_account_for_approval" => array (
                'subject' => "RCPS: New Account Request - {:user_forenames} {:user_surname}",
                'body'    => file_get_contents("new_account_for_approval.txt")
            ),
            "new_account_request_approval" => array (
                'subject' => "RCPS: Request Approved",
                'body'    => file_get_contents("new_account_request_approval.txt")
            ),
            "rcps_notify_request_approval" => array (
                'subject' => "AppForm Req Notify: {:user_username} approved in {:consortium}",
                'body'    => file_get_contents("rcps_notify_request_approval.txt")
            ),
            "rcps_notify_request_declined" => array (
                'subject' => "AppForm Req Notify: {:user_username} declined by {:acting_user}",
                'body'    => file_get_contents("rcps_notify_request_declined.txt")
            )
        );

        return $templates[$template_name];

    }

    public function template_part_process($template_string, $info) {
        // This regex should match up to 2 levels of . indexing into an array
        $output = preg_replace_callback("/\{:([^\s.]+)(?:\.([^\s]+)|)(?:\.([^\s]+)|)\}/",
            function($m) use ($info) {
                if (array_key_exists (2, $m)) {
                    if (array_key_exists (3, $m)) {
                        return       $info[$m[1]][$m[2]][$m[3]];
                    } else {
                        return $info[$m[1]][$m[2]];
                    }
                } else {
                    return $info[$m[1]];
                }
            },
            $template_string
        );

        return $output;
    }

    public function send_mail($template_name, $addresses_to_mail, $info) {
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
        $headers .= "Reply-to: Research Computing Support <rc-support@ucl.ac.uk> \r\n";
        $headers .= "From: Research Computing Support <rc-support@ucl.ac.uk> \r\n";

        return mail($to, $subject, $body, $headers);
    }

}

?>
