<?php 

class SQLActor {
    private $my_db_hostname;
    private $my_db_name    ;
    private $my_db_port    ;
    private $my_db_username;
    private $my_db_password;
    private $dbc;
    private $cache;

    public function __construct( ) {
        $this->my_db_hostname = "localhost";
        $this->my_db_name     = "test";
        $this->my_db_port     = "3306";
        $this->my_db_username = "root";
        $this->my_db_password = ""; 
        $this->cache          = array();
    }

    public function connect() {
        // Create a new connection.
        // The PDO options we pass do the following:
        // \PDO::ATTR_ERRMODE enables exceptions for errors.  This is optional but can be handy.
        // \PDO::ATTR_PERSISTENT disables persistent connections, which can cause concurrency issues in certain cases.  See "Gotchas".
        // \PDO::MYSQL_ATTR_INIT_COMMAND alerts the connection that we'll be passing UTF-8 data.  This may not be required depending on your configuration, but it'll save you headaches down the road if you're trying to store Unicode strings in your database.  See "Gotchas".
        try {
            $this->dbc = new \PDO(
                "mysql:host={$this->my_db_hostname};".
                "por={$this->my_db_port};".
                "dbname={$this->my_db_name}",
                $this->my_db_username,
                $this->my_db_password,
                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
                )
            );
        } catch (\PDOException $e) {
            echo "<h2>There was an error connecting to the database: " . htmlspecialchars($e->getMessage()) . "</h2>";
            throw new Exception($e->getMessage(), 0, $e);
        }
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

    public function get_user_id_consortium_permissions($user_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Consortium_Permissions WHERE privileged_user_id = ?");
        $dbh->bindValue(1, $user_id);
        $dbh->execute();
        return $dbh->fetchAll();
    }

    public function get_user_info($username) {
        $dbh = $this->dbc->prepare("SELECT * FROM Privileged_Users WHERE username = ?");
        $dbh->bindValue(1, $username);
        $dbh->execute();
        return $dbh->fetch();
    }

    public function get_last_status_info($project_id) {
        // Should return the current status (textual) of a request.
        $dbh = $this->dbc->prepare(
            "SELECT event_type,update_time,acting_user FROM Event_Types".
            " RIGHT JOIN Request_Progress".
            " ON (Request_Progress.event_type_id = Event_Types.id)".
            " WHERE Request_Progress.project_id=?".
            " ORDER BY Request_Progress.update_time".
            " DESC LIMIT 1"
        );
        $dbh->bindValue(1, $project_id);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result; // Returns FALSE if there are no rows
    }

    public function get_last_status_text($project_id) {
        $status_info = $this->get_last_status_info($project_id);
        return $status_info['event_type'];
    }
    
    public function get_last_status_time($project_id) {
        $status_info = $this->get_last_status_info($project_id);
        return $status_info['update_time'];
    }
    
    public function get_last_status_user($project_id) {
        $status_info = $this->get_last_status_info($project_id);
        return $status_info['acting_user'];
    }

    public function get_account_request($account_request_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Account_Requests WHERE id=?");
        $dbh->bindValue(1, $account_request_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_project_request($project_request_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Projects WHERE id=?");
        $dbh->bindValue(1, $project_request_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_request_pair($account_request_id, $project_request_id) {
        $project = $this->get_project_request($project_request_id);
        $account = $this->get_account_request($project['request_id']);
        if ($project['request_id'] != $account_request_id) {
            return FALSE;
        } else {
            return array($account,$project);
        }
    }

    public function get_consortia() {
        // Get an array of assoc arrays of consortia + their ids
        return $this->get_table("Consortia");
    }

    public function get_table($table) {
        // Returns assoc array containing ids and contents for the list tables
        if (! isset($this->cache[$table])) {
            $dbh = $this->dbc->prepare("SELECT * from {$table}");
            $dbh->execute();
            $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
            $this->cache[$table] = $results;
        }
        return $this->cache[$table];
    }

    public function options_from_table($table, $column_name) {
        // Returns HTML for filling a dropdown with id vs column entry.
        $options = "";
        $rows = $this->get_table($table);
        foreach ($rows as $row) {
            $options .= "<option value=\"".$row['id']."\">".$row[$column_name]."</option>\n";
        }
        return $options;
    }

    public function get_user_experience_levels() {
        // Get an array of hashes of user_experience_levels with their ids
        return $this->get_table("Experience_Levels");
    }

    public function get_services() {
        // Get an array of hashes of services with their ids
        return $this->get_table('Services');
    }

    public function get_event_types() {
        // Get an array of hashes of services with their ids
        return $this->get_table("Event_Types");
    }

    public function get_event_id_by_name($event_name) {
        // TODO: Replace with an SQL query
        // Returns the id for a given event name
        $event_types = $this->get_event_types();
        foreach ($event_types as $event_type) {
            if ($event_type['event_type'] == $event_name) {
                return $event_type['id'];
            }
        }
        return -1;
    }

    public function get_event_name_by_id($event_id) {
        //TODO: Replace with an SQL query
        // Returns the id for a given event name
        $event_types = $this->get_event_types();
        foreach ($event_types as $event_type) {
            if ($event_type['id'] == $event_id) {
                return $event_type['event_type'];
            }
        }
        return -1;
    }

    //public function get_mail_template($template_name) {
        // I'm not sure whether to store the email templates in the
        //  database or in PHP, but if they're in the database this will retrieve them
    //}

    //public function add_new_project_request ($account, $project) {
        // check whether user has existing acct info
        // add if not
        // add project entry
        // add submitted time
        // add associated service requests
        // mail should be handled elsewhere
    //}

    public function does_user_have_existing_account_request($username) {
        $dbh = $this->dbc->prepare("SELECT COUNT(id) from Account_Requests WHERE username = ?");
        $dbh->bindValue(1,$username);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        if ($results != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_consortium_id($consortium) {
        $dbh = $this->dbc->prepare("SELECT id from Consortia WHERE full_name = ?");
        $dbh->bindValue(1,$consortium);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        return $results;
    }

    public function get_consortium_name($consortium_id) {
        $dbh = $this->dbc->prepare("SELECT full_name from Consortia WHERE id = ?");
        $dbh->bindValue(1,$consortium_id);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        return $results;
    }

    public function get_status_id($status_name) {
        $dbh = $this->dbc->prepare("SELECT id from Event_Types WHERE event_type = ?");
        $dbh->bindValue(1,$status_name);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        return $results;
    }

    public function get_consortium_leaders_to_mail($consortium_id) {   
        $dbh = $this->dbc->prepare(
            "SELECT pu.email_address FROM ".
            "Privileged_Users pu, Consortium_Permissions cp WHERE ".
            "cp.privileged_user_id = pu.id AND ".
            "cp.approves_for_consortium = ? AND ".
            "pu.receives_emails = TRUE"
        );
        $dbh->bindValue(1,$consortium_id, PDO::PARAM_INT);
        $dbh->execute();
        $results = $dbh->fetchAll(PDO::FETCH_COLUMN, 0);
        return $results;
    }

    public function create_new_account_request($request) {
        // Create user account section
        $values_array = array(
            'username', 
            'user_upi',
            'user_type_id',
            'user_email',
            'user_contact_number',
            'user_surname',
            'user_forenames',
            'user_forename_preferred',
            'user_dept',
            'supervisor_name',
            'supervisor_email',
            'experience_level_id',
            'experience_text'
        );
        $values       = implode(",", $values_array);
        $named_params = ":" . implode(",:", $values_array);

        $dbh = $this->dbc->prepare(
            "INSERT INTO Account_Requests ".
            "({$values})" .
            " VALUES " .
            "({$named_params})"
        );

        foreach ($values_array as $value) {
            $dbh->bindParam(":{$value}", $request[$value]);
        };
        
        $account_request_creation_result = $dbh->execute();

        // Next, project section
        // This one's a little more complicated because there are t/f fields that don't get submitted if not checked
        // So, we keep them separately to the main data fields, and check and set them in the main array

        $project = $request['project'];

        $list_of_checkboxes = array(
            'work_type_basic',
            'work_type_array',
            'work_type_multithread',
            'work_type_all_the_ram',
            'work_type_small_mpi',
            'work_type_mid_mpi',
            'work_type_large_mpi',
            'work_type_small_gpu',
            'work_type_large_gpu',
            'is_collab_bristol',
            'is_collab_oxford',
            'is_collab_soton',
            'is_collab_other'
        );
        foreach ($list_of_checkboxes as $key) {
            if (isset($project['checkboxes'][$key])) {
                $project[$key] = 1;
            } else {
                $project[$key] = 0;
            }
        }

        $project['checkboxes']="";
        unset($project['checkboxes']);

        // Then use the same procedure as above. Slight tweak to add 
        //  username and request id prevents straight code re-use.
        $values_array = array(
            'is_funded',
            'consortium_id',
            'weird_tech_description',
            'work_description',
            'applications_description',
            'collab_bristol_name',
            'collab_oxford_name',
            'collab_soton_name',
            'collab_other_institute',
            'collab_other_name',
            'pi_email',
            'work_required_collated',
            'collaboration_collated',
            'work_type_basic',
            'work_type_array',
            'work_type_multithread',
            'work_type_all_the_ram',
            'work_type_small_mpi',
            'work_type_mid_mpi',
            'work_type_large_mpi',
            'work_type_small_gpu',
            'work_type_large_gpu',
            'is_collab_bristol',
            'is_collab_oxford',
            'is_collab_soton',
            'is_collab_other'
        );
        $values       = implode(",", $values_array);
        $named_params = ":" . implode(",:", $values_array);

        $dbh = $this->dbc->prepare(
            "INSERT INTO Projects ".
            "(username, request_id, $values)" .
            " VALUES " .
            "(:username, :request_id, $named_params)"
        );

        $account_request_id = $this->dbc->lastInsertId();
        $dbh->bindParam(":username", $request['username']);
        $dbh->bindParam(":request_id", $account_request_id);
        foreach ($values_array as $value) {
            $dbh->bindParam(":$value", $project[$value]);
        };
        
        $project_request_creation_result = $dbh->execute();
        $project_request_id = $this->dbc->lastInsertId();
        
        // Finally, mark as submitted in the events table

        // And return the request id if everything worked
        if ($account_request_creation_result &&
            $project_request_creation_result ) {
                return array('account_request_id' => $account_request_id, 
                             'project_request_id' => $project_request_id
                         );
        } else {
            return FALSE;
        }
    }


    function mark_request_status($account_request_id, $project_id, $acting_user, $status_string, $comment) {
        $status_id = $this->get_status_id($status_string);

        $dbh = $this->dbc->prepare(
            "INSERT INTO Request_Progress ".
            "(account_id, project_id, event_type_id, acting_user, with_comment)" .
            " VALUES " .
            "(:account_id, :project_id, :event_type_id, :acting_user, :with_comment)"
        );

        $dbh->bindParam(":account_id", $account_request_id);
        $dbh->bindParam(":project_id", $project_id);
        $dbh->bindParam(":event_type_id", $status_id);
        $dbh->bindParam(":acting_user", $acting_user);
        $dbh->bindParam(":with_comment", $comment);

        return $dbh->execute();
    }

} // End of class

