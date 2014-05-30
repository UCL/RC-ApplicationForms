<?php

//All config options


// These are classes because using either global or GLOBALS was being bloody weird
class DBSettings {
    static public $db_hostname = "localhost";
    static public $db_name     = "rcps_accounts";
    static public $db_port     = "3306";
    static public $db_username = "root";
    static public $db_password = "";
}

class SiteSettings {
    static public $base_url = "";
}

class MailSettings {
    static public $never_send_mail = TRUE;
    static public $override_mail   = TRUE;
    static public $override_mail_address = "i.kirker@ucl.ac.uk";
}
