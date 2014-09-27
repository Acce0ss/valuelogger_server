<?php

//table names:
$prefix	= "paino_";
$tables = array( 
  "measurement" => $prefix . "measurement",
  "dataserie" => $prefix . "dataserie",
  "user" => $prefix . "user",
  "serie_visibility" => $prefix . "serie_visibility",
  "friends" => $prefix . "friends"
);
$host = "localhost";
$dbname = "paino_new_db";
$user = "paino_api";
$pass = "oniap";


$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
