<?php

require_once('messages.php');
require_once('database/database_connection.php');
require_once('helpers/authentication.php');

function get_latest_value($request)
{

  if($request->isPost())
  {

    $db = get_db();
    $tables = get_tables();

    $data = json_decode($request->getBody(), true);

    check_fields($data, array(
      'user',
      'password'
    ));

    if(!verify_pw($data['user'], $data['password']))
    {
      return;
    }

    $query = "";

    try {

      $db->beginTransaction();

      $query = "SELECT user.id AS user_id, meas.value AS value, meas.id AS measurement_id, meas.dataserie_id, serie.name, meas.timestamp AS time" .
	       " FROM $tables[user] AS user, ". 
	       "      $tables[measurement] AS meas, ".
	       "      $tables[dataserie] AS serie ".

	       " WHERE timestamp IN (SELECT MAX(timestamp) FROM $tables[measurement]) AND " . 
	       " user.username=:user AND " .
	       " meas.adder_id=user.id AND " .
	       " meas.dataserie_id=serie.id";

      $prepared = $db->prepare($query);
      
      $prepared->bindParam(':user', $data['user']);

      $prepared->execute();

      $user_row;
      
      foreach($prepared as $row)
      {
	$user_row = $row;
      }

      if(!isset($user_row['user_id']))
      {
	$db->rollback();
	throw_error(USER_ID_NOT_FOUND());
      }

      $db->commit();

    }
    catch(PDOException $ex)
    {

      $db->rollBack();

      error_log("[GET_LATEST_VALUE][$query] " . $ex->getMessage());

      return json_encode(UNEXPECTED_ERROR());
     
    }
    $msg_array = SUCCESS();
    $msg_array['data'] = array(
      'user' => array('id'=>$user_row['user_id']),
      'measurement' => array(
	'id' => $user_row['measurement_id'],
	'value' => $user_row['value'],
	'timestamp' => $user_row['time']
      ),
      'serie' => array(
	'id' => $user_row['dataserie_id'],
	'name' => $user_row['name']
      )
    );
    return json_encode($msg_array);
  }
}

?>
