<?php

require_once('messages.php');
require_once('database/database_connection.php');
require_once('helpers/authentication.php');

function add_value($request)
{

  if($request->isPost())
  {

    $db = get_db();
    $tables = get_tables();

    $data = json_decode($request->getBody(), true);

    check_fields($data, array(
      'user',
      'password',
      'value',
      'serie_id'
    ));

    if(!verify_pw($data['user'], $data['password']))
    {
      return;
    }

    $query = "";

    try {

      $db->beginTransaction();

      $query = "SELECT user.id,user.authlevel,perm.authlevel " .
	       " FROM $tables[user] AS user, ". 
	       "      $tables[serie_permission] AS perm ".
	       " WHERE user.username=:user AND " .
	       " perm.dataserie_id=:serie_id AND " .
	       " perm.user_id=user.id";

      $prepared = $db->prepare($query);
      
      $prepared->bindParam(':user', $data['user']);
      $prepared->bindParam(':serie_id', $data['serie_id']);

      $prepared->execute();

      $user_row;
      

      foreach($prepared as $row)
      {
	$user_row = $row;

      }

      if(!isset($user_row['id']))
      {
	$db->rollback();
	throw_error(USER_ID_NOT_FOUND());
      }

      $query = "INSERT INTO " .
	       " $tables[measurement] " .
	       " ( timestamp, value, dataserie_id," .
	       "   adder_id) VALUES(:time, :value, " .
	       "                    :data_id, :user_id)";
      
      $prepared = $db->prepare($query);
      
      $prepared->bindParam(':time', time());
      $prepared->bindParam(':value', $data['value']);
      $prepared->bindParam(':data_id', $data['serie_id']);
      $prepared->bindParam(':user_id', $user_row['id']);

      $prepared->execute();

      $db->commit();

    }
    catch(PDOException $ex)
    {

      $db->rollBack();

      error_log("[ADD_VALUE][$query] " . $ex->getMessage());

      return json_encode(UNEXPECTED_ERROR());
     
    }

    return json_encode(SUCCESS());
  }
}

?>
