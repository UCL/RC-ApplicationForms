<?php

class MailMessage {
    protected $never_send_mail = TRUE;
    protected $to = "nobody@localhost";
    protected $subject = "(No Subject Provided)";
    protected $additional_headers = NULL;
    protected $additional_parameters = NULL;
    protected $message = "(No Message Provided)";
    protected $sent = FALSE;

    // Type checking done by hinting in prototypes
    // The long set_all prototype is designed to replicate the mail function call prototype.

    public function set_all     ($to,
                                 $subject,
                                 $message,
                                 $additional_headers = NULL,
                                 $additional_parameters = NULL)
    {
        $this->set_recipient($to);
        $this->set_subject($subject);
        $this->set_headers($additional_headers);
        $this->set_parameters($additional_parameters);
        $this->set_message($message);
    }

    private function check_string($arg) {
        if (! ( is_string($arg) || is_null($arg) ) ) {
            throw new Exception('Attempt to set mail field to non-string.');
        }
    }

    public function set_recipient($recipient) {
        $this->check_string($recipient);
        $this->to = $recipient;
    }

    public function set_subject($subject) {
        $this->check_string($subject);
        $this->subject = $subject;
    }

    public function set_headers($headers) {
        $this->check_string($headers);
        $this->additional_headers = $headers;
    }

    public function set_parameters($parameters) {
        $this->check_string($parameters);
        $this->additional_parameters = $parameters;
    }

    public function set_message($message) {
        $this->check_string($message);
        $this->message = $message;
    }

    public function get_recipient () {
        return $this->to;
    }

    public function get_subject() {
        return $this->subject;
    }

    public function get_headers() {
        return $this->additional_headers;
    }

    public function get_additional_parameters() {
        return $this->additional_parameters;
    }

    public function get_message() {
        return $this->message;
    }

    public function send()
    {
        if ($this->never_send_mail == TRUE ) {
            return FALSE;
        }

        $result = mail($this->to,
                       $this->subject,
                       $this->message,
                       $this->additional_headers,
                       $this->additional_parameters);
        $this->sent = $result;
        return $result;
    }
}

?>
