<?php

class DbConnection
{
  private $db;
  private $tables;

  function __construct()
  {
    include('../config.php');
    
    $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", 
			$user, $pass);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //don't change order, since setup_database.php depends on it
    //for deletion of old tables....
    $this->tables = array( 
      "measurement" => $prefix . "measurement",
      "serie_permission" => $prefix . "serie_permission",
      "friends_with" => $prefix . "friends_with",
      "dataserie" => $prefix . "dataserie",
      "user" => $prefix . "user"
    );
  }

  //table names:1~
  public function get_tables()
  {
    return $this->tables;
  }

  public function get_db()
  {
    return $this->db;
  }

}

$db_conn = new DbConnection();

function get_db()
{
  global $db_conn;
  return $db_conn->get_db();
}

function get_tables()
{
  global $db_conn;
  return $db_conn->get_tables();
}

?>
