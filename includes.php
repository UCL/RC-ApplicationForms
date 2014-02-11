<?php
    include "db_settings.php";

    function get_consortia() {
      global $my_db_hostname, $my_db_port, $my_db_name, $my_db_username, $my_db_password;
      try{
        $link = new \PDO(   "mysql:host={$my_db_hostname};por={$my_db_port};dbname={$my_db_name}",
                            $my_db_username,
                            $my_db_password,
                            array(
                                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                                \PDO::ATTR_PERSISTENT => false,
                                \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8mb4'
                            )
                          );
        $handle = $link->prepare("select * from consortia;");
        $handle->execute();
        $results = $handle->fetchAll(\PDO::FETCH_NUM);
        return $results;
      }
      catch(\PDOException $ex){
        print($ex->getMessage());
        return False;
      }
    }

    function options_from_consortia() {
        $options = "";
        $consortia = get_consortia();
        foreach ($consortia as $consortium) {
            $options += "<option value=\"{$consortium['id']}\">{$consortium['full_name']}</option>\n";
        }
        return $options;
    }

?>
 
