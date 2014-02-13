<?php 

class sql_actor {
    private $my_db_hostname;
    private $my_db_name    ;
    private $my_db_port    ;
    private $my_db_username;
    private $my_db_password;
    private $dbc;
    private $consortia;
    private $user_experience_levels;
    private $event_types;
    private $services;

    public function __construct( ) {
        $this->my_db_hostname = "127.0.0.1";
        $this->my_db_name     = "test";
        $this->my_db_port     = "3306";
        $this->my_db_username = "root";
        $this->my_db_password = ""; 
    }

    public function connect() {
        // Create a new connection.
        // The PDO options we pass do the following:
        // \PDO::ATTR_ERRMODE enables exceptions for errors.  This is optional but can be handy.
        // \PDO::ATTR_PERSISTENT disables persistent connections, which can cause concurrency issues in certain cases.  See "Gotchas".
        // \PDO::MYSQL_ATTR_INIT_COMMAND alerts the connection that we'll be passing UTF-8 data.  This may not be required depending on your configuration, but it'll save you headaches down the road if you're trying to store Unicode strings in your database.  See "Gotchas".
        $this->dbc = new \PDO(
            "mysql:host={$this->my_db_hostname};".
            "por={$this->my_db_port};".
            "dbname={$this->my_db_name}",
            $this->my_db_username,
            $this->my_db_password,
            array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8mb4'
            )
        );
    }

    public function disconnect() {
        $this->dbc = NULL;
        // PHP will clean up the connection with the PDO class destructor.
    }

    public function is_privileged_user($user) {
        // Should return T/F whether a user has any special permissions.
        // (i.e. if they're a consortium leader or rainbow dash)
        // It's a bit approximate at the moment, since Privileged_Users do not
        //  necessarily have any special permissions.
        $dbh = $this->dbc->prepare("SELECT COUNT(id) FROM Privileged_Users WHERE username = ?");
        $dbh->bindValue(1,$user);
        $dbh->execute();
        $result = $dbh->fetchColumn(0);

        if ($result > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_superuser($user) {
        // Should return T/F whether a user has all permissions.
        $dbh = $this->dbc->prepare(
            "SELECT COUNT(id) FROM Privileged_Users ". 
            "WHERE username = ? AND super_special_rainbow_pegasus_powers = TRUE"
        );
        $dbh->bindValue(1,$user);
        $dbh->execute();
        $result = $dbh->fetchColumn(0);
        if ($result > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_approvable_by($object_id, $user) {
        // Should simply return true if $user can approve $object_id, otherwise false
        
        // need consortium of request, joined against consortia user can approve

        // also need to check whether request is already approved
        $dbh = $this->dbc->prepare("SELECT COUNT(id) FROM Requests");

    }

    public function approve_request($object_id, $user) {
        // Should mark a thing as approved.
        // Mail should happen elsewhere.
        if ($this->is_approvable_by($object_id, $user) == FALSE) {
            return FALSE;
        };
        $dbh = $this->dbc->prepare(
            "INSERT INTO Request_Progress ".
            " (request_id, event_type_id, acting_user, object) ".
            " VALUES (?, ?, ?, ?)"
        );
        $dbh->bindValue(1, $object_id, PDO::PARAM_INT);
        $dbh->bindValue(2, $this->get_event_id_by_name("approved"));
        $dbh->bindValue(3, $user);
        $dbh->bindValue(4, "");
        return $dbh->execute();
    }

    public function get_request_status($object_id, $user) {
        // Should return the current status (textual) of a request.
        $dbh = $this->dbc->prepare(
            "SELECT * FROM Request_Progress".
            " WHERE request_id = ? ".
            " ORDER BY update_time DESC LIMIT 1;"
        );
        $dbh->bindValue(1, $object_id);
        $dbh->execute();
        $result = $dbh->fetch(PDO::FETCH_ASSOC);
        if ( $this->can_view($object_id, $user) or
             $this->is_approvable_by($object_id, $user) )
        {
            return $this->event_name_by_id($result['event_type_id']);
        } else {
            return FALSE;
        }
    }

    public function get_request_owner($object_id) {
        // Return the username associated with $object_id
        $dbh = $this->dbc->prepare("SELECT username FROM XXXX WHERE id=?;");
        $dbh->bindValue(1, $object_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetchAll(PDO::FETCH_NUM); // Should only return one row, one value. Look up appropriate call for this.
        return $result;
    }

    public function get_request_with_actions($object_id, $user) {
        // Get all the information about a request as a hash plus 
        //  the two extra T/F columns mentioned that show 
        //  edit/approval permissions

    }

    public function get_requests_with_actions($user) {
        // Get an array of hashes representing all the requests 
        //  visible to a user *plus* two extra TRUE/FALSE columns 
        //  showing whether they have permission to edit and/or approve
        //  those requests
    }

    public function get_consortia() {
        // Get an array of hashes of consortia with their ids
        if (isset($this->consortia)) {
            return $this->consortia;
        } else {
            $dbh = $this->dbc->prepare("SELECT * from Consortia");
            $dbh->execute();
            $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
            $this->consortia = $results;
        }
    }
    
    public function get_user_experience_levels() {
        // Get an array of hashes of user_experience_levels with their ids
        if (isset($this->user_experience_levels)) {
            return $this->user_experience_levels;
        } else {
            $dbh = $this->dbc->prepare("SELECT * from User_Experience_Levels");
            $dbh->execute();
            $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
            $this->user_experience_levels = $results;
            return $this->user_experience_levels;
        }
    }

    public function get_services() {
        // Get an array of hashes of services with their ids
        if (isset($this->services)) {
            return $this->services;
        } else {
            $dbh = $this->dbc->prepare("SELECT * from Services");
            $dbh->execute();
            $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
            $this->services = $results;
            return $this->services;
        }
    }

    public function get_event_types() {
        // Get an array of hashes of services with their ids
        if (isset($this->event_types)) {
            return $this->event_types;
        } else {
            $dbh = $this->dbc->prepare("SELECT * from Event_Types");
            $dbh->execute();
            $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
            $this->event_types = $results;
            return $this->event_types;
        }
    }

    public function get_event_id_by_name($event_name) {
        // Returns the id for a given event name
        $event_types = $this->get_event_types();
        foreach ($event_type as $event_types) {
            if ($event_type['event_type'] == $event_name) {
                return $event_type['id'];
            }
        }
        return -1;
    }

    public function get_event_name_by_id($event_id) {
        // Returns the id for a given event name
        $event_types = $this->get_event_types();
        foreach ($event_type as $event_types) {
            if ($event_type['id'] == $event_name) {
                return $event_type['event_type'];
            }
        }
        return -1;
    }

    public function get_mail_template($template_name) {
        // I'm not sure whether to store the email templates in the
        //  database or in PHP, but if they're in the database this will retrieve them
    }

    public function add_new_account ($account) {

    }

    public function add_new_project ($account, $project) {
    }

    public function add_new_service_request ($account, $project, $service_request) {
    }

}

