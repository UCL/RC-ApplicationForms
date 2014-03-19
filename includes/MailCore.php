<?php

class MailCore {
    //Wrapper class to allow mocking in tests
    public function send(string $to, string $subject, string $message, 
                         string $additional_headers=NULL, 
                         string $additional_paramers=NULL) 
    {
        return mail($to, $subject, $message, $additional_headers, $additional_parameters);
    }
}

?>
