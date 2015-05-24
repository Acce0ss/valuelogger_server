<?php

require_once('messages.php');
require_once('database/database_connection.php');
require_once('helpers/authentication.php');

function values_for_serie($request)
{

  if($request->isPost())
  {

    $data = json_decode($request->getBody(), true);

    check_fields($data, array(
      'user',
      'password',
      'serie_id'
    ));

    if(!verify_pw($data['user'], $data['password']))
    {
      //message thrown in verify_pw, return...
      return;
    }

    $db = get_db();
    $tables = get_tables();

    $query = "";

    $msg_array = SUCCESS();
    $msg_array['data'] = array();
    $msg_array['data']['measurements'] = array();

    try {

      $db->beginTransaction();

      $between="";

      //check if date range was given
      if(isset($data['time']) && isset($data['time']['start']) && isset($data['time']['end']))
      {
	$between = " meas.timestamp BETWEEN :start AND :end";
      }

      $query = "SELECT user.id AS user_id, meas.value AS value, meas.adder_id, meas.id AS measurement_id, meas.dataserie_id, serie.name, meas.timestamp AS time, perm.authlevel AS auth" .
	       " FROM $tables[user] AS user, ". 
	       "      $tables[measurement] AS meas, ".
	       "      $tables[dataserie] AS serie, ".
	       "      $tables[serie_permission] AS perm ".

	       " WHERE " . 
	       " user.username=:user AND " .
	       " serie.id=:serie_id AND " .
	         $between .
	       " meas.adder_id=user.id AND " .
	       " perm.user_id=user.id AND " .
	       " perm.dataserie_id=serie.id AND " .
	       " meas.dataserie_id=serie.id ORDER BY meas.timestamp ASC";

      $prepared = $db->prepare($query);
      
      $prepared->bindParam(':user', $data['user']);
      $prepared->bindParam(':serie_id', $data['serie_id']);

      //check if date range was given and bind if needed.
      if($between !== "")
      {
	$prepared->bindParam(':start', $data['time']['start']);
	$prepared->bindParam(':end', $data['time']['end']);
      }

      $prepared->execute();
      
      $result = $prepared->fetchAll();

      if(!isset(reset($result)['user_id']))
      {
	$db->rollback();
	throw_error(USER_ID_NOT_FOUND());
      }

      $msg_array['user'] = array('id'=> reset($result)['user_id']);     
      $msg_array['serie'] = array(
	'id' => reset($result)['dataserie_id'],
	'name' => reset($result)['name']
      );
      $msg_array['time'] = array(
	'start' => reset($result)['time'],
	'end' => end($result)['time']
      ); 

      foreach($result as $row)
      {
	$meas_data = array(
	  'id' => $row['measurement_id'],
	  'value' => $row['value'],
	  'timestamp' => $row['time'],
	  'adder_id' => $row['adder_id']
	);

	array_push($msg_array['data']['measurements'], $meas_data);
	
      }

      $db->commit();

    }
    catch(PDOException $ex)
    {

      $db->rollBack();

      error_log("[GET_LATEST_VALUE][$query] " . $ex->getMessage());

      return json_encode(UNEXPECTED_ERROR());
     
    }

    return json_encode($msg_array);
  }
}

?>
