<?php

class SQLActor {
    private $my_db_hostname;
    private $my_db_name    ;
    private $my_db_port    ;
    private $my_db_username;
    private $my_db_password;

    /** @var $dbh PDO  */ /* Fixes method syntax checking in PHPStorm */
    private $dbc;
    private $cache;

    public function __construct( ) {
        $this->my_db_hostname = "localhost";
        $this->my_db_name     = "rcps_accounts";
        $this->my_db_port     = "3306";
        $this->my_db_username = "root";
        $this->my_db_password = "";
        $this->cache          = array();
    }

    public function connect($PDOobject=NULL) {
        // Create a new connection.
        // The PDO options we pass do the following:
        // \PDO::ATTR_ERRMODE enables exceptions for errors.  This is optional but can be handy.
        // \PDO::ATTR_PERSISTENT disables persistent connections, which can cause concurrency issues in certain cases.  See "Gotchas".
        // \PDO::MYSQL_ATTR_INIT_COMMAND alerts the connection that we'll be passing UTF-8 data.  This may not be required depending on your configuration, but it'll save you headaches down the road if you're trying to store Unicode strings in your database.  See "Gotchas".
        if ($PDOobject == NULL) {
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
        } else {
            $this->dbc = $PDOobject;
        }
    }

    public function disconnect() {
        $this->dbc = NULL;
        // PHP will clean up the connection with the PDO class destructor.
    }

    public function get_user_info($username) {
        $dbh = $this->dbc->prepare("SELECT * FROM Privileged_Users WHERE username = ? ORDER BY id DESC LIMIT 1");
        $dbh->bindValue(1, $username);
        $dbh->execute();
        return $dbh->fetch();
    }

    public function get_research_theme_id($status_name) {
        $dbh = $this->dbc->prepare("SELECT * FROM Research_Themes WHERE full_name=?");
        $dbh->bindValue(1, $theme_name);
        $dbh->execute();
        $result = $dbh->fetchColumn(0);
        return $result; // Returns FALSE if there are no rows
    }

    public function get_research_theme($theme_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Research_Themes WHERE id=?");
        $dbh->bindValue(1, $theme_id);
        $dbh->execute();
        $result = $dbh->fetchColumn(1);
        return $result; // Returns FALSE if there are no rows
    }

    public function get_status_type_id($status_name) {
        $dbh = $this->dbc->prepare("SELECT * FROM Status_Types WHERE status_type=?");
        $dbh->bindValue(1, $status_name);
        $dbh->execute();
        $result = $dbh->fetchColumn(0);
        return $result; // Returns FALSE if there are no rows
    }

    public function get_status_type($status_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Status_Types WHERE id=?");
        $dbh->bindValue(1, $status_id);
        $dbh->execute();
        $result = $dbh->fetchColumn(1);
        return $result; // Returns FALSE if there are no rows
    }

    public function get_last_project_request_status($project_id) {
        // Should return the current status (textual) of a request.
        $dbh = $this->dbc->prepare(
            "SELECT * FROM Project_Request_Statuses".
            " WHERE project_request_id=?".
            " ORDER BY update_time DESC, id DESC".
            " LIMIT 1"
        );
        $dbh->bindValue(1, $project_id);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result; // Returns FALSE if there are no rows
    }

    public function get_user_profile($user_profile_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM User_Profiles WHERE id=? ORDER BY id DESC LIMIT 1");
        $dbh->bindValue(1, $user_profile_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_user_profile_by_name($username) {
        $dbh = $this->dbc->prepare("SELECT * FROM User_Profiles WHERE username=? ORDER BY id DESC LIMIT 1");
        $dbh->bindValue(1, $username);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_all_user_profiles() {
        $dbh = $this->dbc->prepare("SELECT * FROM User_Profiles");
        $dbh->execute();
        $result = $dbh->fetchAll();
        return $result;
    }

    public function get_all_profiles_for_one_username($username) {
        $dbh = $this->dbc->prepare("SELECT * FROM User_Profiles WHERE username=?");
        $dbh->bindValue(1, $username);
        $dbh->execute();
        $result = $dbh->fetchAll();
        return $result;
    }

    public function get_all_unique_user_profiles() {
        $dbh = $this->dbc->prepare(
            "SELECT * FROM " .
            "(SELECT MAX(id) as id,username from User_Profiles GROUP BY username)" .
            " AS Most_Recent_IDs ".
            " LEFT JOIN User_Profiles ON ".
            " Most_Recent_IDs.id = User_Profiles.id"
        );
        $dbh->execute();
        $result = $dbh->fetchAll();
        return $result;
    }

    public function get_project_request($project_request_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Project_Requests WHERE id=?");
        $dbh->bindValue(1, $project_request_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_all_project_requests() {
        $dbh = $this->dbc->prepare("SELECT * FROM Project_Requests");
        $dbh->execute();
        $result = $dbh->fetchAll();
        return $result;
    }

    public function get_publication($publication_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Publications WHERE id=?");
        $dbh->bindValue(1, $publication_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
    }

    public function get_research_project_code($code_id) {
        $dbh = $this->dbc->prepare("SELECT * FROM Research_Project_Code WHERE id=?");
        $dbh->bindValue(1, $code_id, PDO::PARAM_INT);
        $dbh->execute();
        $result = $dbh->fetch();
        return $result;
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
        return $this->get_table("Status_Types");
    }

    public function does_user_have_existing_project_request($username) {
        $dbh = $this->dbc->prepare("SELECT COUNT(id) from User_Profiles as u,Project_Requests as p" .
                                   " WHERE u.id = p.user_profile_id AND u.username = ?");
        $dbh->bindValue(1, $username);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        if ($results != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_status_id($status_name) {
        $dbh = $this->dbc->prepare("SELECT id from Status_Types WHERE status_type = ?");
        $dbh->bindValue(1,$status_name);
        $dbh->execute();
        $results = $dbh->fetchColumn(0);
        return $results;
    }

    public function save_object_data($table, $object_data) {
        $data_keys = array_keys($object_data);
        $values       = implode(",", $data_keys);
        $named_params = ":" . implode(",:", $data_keys);

        $dbh = $this->dbc->prepare(
            "INSERT INTO $table " .
            "({$values})" .
            " VALUES " .
            "({$named_params}) "
        );

        foreach ($data_keys as $key) {
            $dbh->bindParam(":{$key}", $object_data[$key]);
        };

        $save_result = $dbh->execute();
        $created_row_id = $this->dbc->lastInsertId();

        if ($save_result) {
            return $created_row_id;
        } else {
            return FALSE;
        }
    }

    public function save_user_profile($profile_data) {
        return $this->save_object_data("User_Profiles", $profile_data);
    }

    public function save_project_request($request_data) {
        return $this->save_object_data("Project_Requests", $request_data);
    }

    public function save_publication($publication_data) {
        return $this->save_object_data("Publications", $publication_data);
    }

    public function save_research_project_code($code_data) {
        return $this->save_object_data("Research_Project_Codes", $code_data);
    }

    function mark_request_status($project_request_id, $acting_user, $status_string, $comment) {
        $status_id = $this->get_status_id($status_string);

        $dbh = $this->dbc->prepare(
            "INSERT INTO Project_Request_Statuses ".
            "(project_request_id, status_type_id, acting_user, comment)" .
            " VALUES " .
            "(:project_request_id, :status_type_id, :acting_user, :comment)"
        );

        $dbh->bindParam(":project_request_id", $project_request_id);
        $dbh->bindParam(":status_type_id", $status_id);
        $dbh->bindParam(":acting_user", $acting_user);
        $dbh->bindParam(":comment", $comment);

        return $dbh->execute();
    }

} // End of class
