<?php

require_once(__DIR__ . "/../database/database_connection.php");
require_once(__DIR__ . "/password.php");
require_once(__DIR__ . "/../messages.php");

function generate_pw($plain)
{


return password_hash($plain, PASSWORD_DEFAULT);

}

function verify_pw($user, $plain)
{
  $db = get_db();
  $tables = get_tables();

  $stored_hash = "";
  $query ="";

  try {

    $db->beginTransaction();

    $query = "SELECT password FROM $tables[user] WHERE username=:user";

    $prepared = $db->prepare($query);

    $prepared->bindParam(':user', $user);

    $prepared->execute();

    $db->commit();

    foreach($prepared as $row)
    {
      $stored_hash = $row['password'];
    }

    if($stored_hash === "")
    {
      throw new Exception(json_encode(USER_DOES_NOT_EXIST($user)));
    }

  }
  catch(PDOException $ex)
  {

    $db->rollBack();

    error_log("[PW_AUTH][$query] " . $ex->getMessage());

    return 0;
  }

  if (!password_verify($plain, $stored_hash)) {
    throw new Exception(json_encode(PASSWORD_WRONG($user)));
    return 0;
  }

  return 1;

}

?>