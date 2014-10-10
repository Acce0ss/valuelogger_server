<?php

require(__DIR__ . '/config.php');
require_once(__DIR__ . '/translations/' . $language . '.php');

function check_fields($data, $array)
{
  $required_fields = array();

  foreach($array as $key)
  {
    if(!isset($data[$key]))
    {
      array_push($required_fields, $key);
    }
  }

  if(count($required_fields) > 0)
  {

    $error_message = REQUIRED_FIELDS_MISSING();
    $error_message['required_fields'] = array();

    foreach($required_fields as $field)
    {
      array_push($error_message['required_fields'], $field);
    }

    throw new Exception(json_encode($error_message, TRUE));
  }
}

function throw_error($array)
{
  throw new Exception(json_encode($array, TRUE));
}


?>