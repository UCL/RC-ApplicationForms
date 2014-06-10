<?php

include_once "includes/config.php";

class MailMailer {

    private $override_mail;
    private $override_mail_address;

    public function __construct() {
        $this->override_mail = MailSettings::$override_mail;
        $this->override_mail_address = MailSettings::$override_mail_address;
    }


    public function send_mail($template_name, $addresses_to_mail,
                              ProjectRequest $project_request,
                              UserProfile $user_profile,
                              Operator $operator,
                              $message_object=NULL) {
        //return TRUE; // Until fixed
        if (is_string($addresses_to_mail)) {
            $addresses_to_mail = array($addresses_to_mail);
        }
        if ($this->override_mail) {
            $stored_addresses = $addresses_to_mail;
            $info['stored_addresses_imploded'] = implode("&x10;",$stored_addresses);
            $addresses_to_mail = array($this->override_mail_address);
        }

        if (!file_exists("templates/{$template_name}.php")) {
            die("Mail template \"$template_name\" not found.\n");
        }

        $template = include("templates/{$template_name}.php");

        $to      = implode(", ", $addresses_to_mail);
        $subject = $template['subject'];
        $body    = $template['body'];
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: Research Computing Support <rc-support@ucl.ac.uk> \r\n";
        if (array_key_exists('override_replyto', $template)) {
            $headers .= "Reply-to: " . $template['override_replyto'] . " \r\n";
        } else {
            $headers .= "Reply-to: Research Computing Support <rc-support@ucl.ac.uk> \r\n";
        }

        // Can provide your own MailMessage object for, e.g., unit testing.
        if ($message_object == NULL) {
            $message_object = new MailMessage();
        }
        $message_object->set_all($to, $subject, $body, $headers);
        $result = $message_object->send();

        return $result;
    }

    public function set_override($override_tf) {
        $this->override_mail = $override_tf;
    }

    public function get_override() {
        return $this->override_mail;
    }

    public function get_override_address() {
        return $this->override_mail_address;
    }

}

?>
