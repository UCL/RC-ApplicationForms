<?php 

class sql_actor {
  private $my_db_hostname;
  private $my_db_name    ;
  private $my_db_port    ;
  private $my_db_username;
  private $my_db_password;
  private $dbc;

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
    $this->dbc = new \PDO( "mysql:host={$this->my_db_hostname};por={$this->my_db_port};dbname={$this->my_db_name}",
                    $this->my_db_username,
                    $this->my_db_password,
                      array(
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_PERSISTENT => false,
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8mb4'
                      )
                    );
  }

  public function is_approvable_by($object_id, $user) {
  }

  public function get_request_owner($object_id) {
    $dbh = $this->dbc->prepare("SELECT username FROM XXXX WHERE id=?;");
    $dbh->bindValue(1, $object_id, PDO::PARAM_INT);
    $dbh->execute();
    $result = $dbh->fetchAll(\PDO::FETCH_NUM); // Should only return one row, one value. Look up appropriate call for this.
    return $result;
  }

  public function approve($object_id, $user) {
    $mh = new mail_handler();

    $mh->send_approval // Don't actually know what we're approving yet. :/

  }

  public function get_request($object_id, $user);
  public function is_privileged_user($user);
  public function get_requests_with_actions($user);

  public function get_my_requests($current_user) {
    $dbh = $this->dbc->prepare("SELECT username FROM XXXX WHERE username=?;");
    $dbh->bindValue(1, $object_id, PDO::PARAM_INT);
    $dbh->execute();
    $results = $dbh->fetchAll(\PDO::FETCH_NUM); 
    return $results;
  }

  public function get_mail_template($template_name);
}

