<?php

require_once(__DIR__ . "/../helpers/authentication.php");

require_once(__DIR__ . '/database_connection.php');


try{

$db = get_db();
$tables = get_tables();

$db->beginTransaction();

foreach($tables as $key => $table)
{
  $query = "DROP TABLE IF EXISTS $table";

  $db->exec($query);
}

$query=	" CREATE TABLE $tables[user] ( " .
	" id INT NOT NULL AUTO_INCREMENT," .
	" username VARCHAR(20) NOT NULL UNIQUE," .
	" authlevel INT NOT NULL DEFAULT 0," .
	" displayname VARCHAR(30) NOT NULL DEFAULT 'Logger'," .
	" joined DATE NOT NULL," .
	" active BOOL NOT NULL DEFAULT 1," .
	" password VARCHAR(60) NOT NULL," .
	" PRIMARY KEY(id)" .
	" )";

$db->exec($query);

$query=	" CREATE TABLE $tables[dataserie] ( " .
	" id INT NOT NULL AUTO_INCREMENT," .
	" name VARCHAR(50) NOT NULL," .
	" PRIMARY KEY(id)" .
	" )";

$db->exec($query);

$query=	" CREATE TABLE $tables[measurement] ( " .
	" id INT NOT NULL AUTO_INCREMENT," .
	" timestamp INT NOT NULL," .
	" value FLOAT NOT NULL," .
	" dataserie_id INT NOT NULL," .
	" adder_id INT NOT NULL," .
	" PRIMARY KEY(id)," .
	" FOREIGN KEY(dataserie_id) REFERENCES $tables[dataserie](id) ON DELETE RESTRICT ON UPDATE CASCADE," .
	" FOREIGN KEY(adder_id) REFERENCES $tables[user](id) ON DELETE RESTRICT ON UPDATE CASCADE" .
	" )";

$db->exec($query);

$query=	" CREATE TABLE $tables[friends_with] ( " .
	" friend1_id INT NOT NULL," .
	" friend2_id INT NOT NULL," .
	" PRIMARY KEY(friend1_id, friend2_id)," .
	" FOREIGN KEY(friend1_id) REFERENCES $tables[user](id) ON DELETE RESTRICT ON UPDATE CASCADE," .
	" FOREIGN KEY(friend2_id) REFERENCES $tables[user](id) ON DELETE RESTRICT ON UPDATE CASCADE" .
	" )";

$db->exec($query);

$query=	" CREATE TABLE $tables[serie_permission] ( " .
	" dataserie_id INT NOT NULL," .
	" user_id INT NOT NULL," .
	" authlevel INT NOT NULL DEFAULT 0," .
	" PRIMARY KEY(dataserie_id, user_id)," .
	" FOREIGN KEY(dataserie_id) REFERENCES $tables[dataserie](id) ON DELETE RESTRICT ON UPDATE CASCADE," .
	" FOREIGN KEY(user_id) REFERENCES $tables[user](id) ON DELETE RESTRICT ON UPDATE CASCADE" .
	" )";

$db->exec($query);

$query=	"INSERT INTO $tables[user](username, password, displayname, joined, authlevel)  " .
	"  values( 'asser'," .
	"      '" . generate_pw('asser')  . "'," .
	"          'Asser'," .
	"          DATE(NOW()), 100)";

$db->exec($query);

$query= " INSERT INTO paino_dataserie(id, name) values(1, 'Test series')";

$db->exec($query);

$query="INSERT INTO paino_serie_permission(dataserie_id, user_id, authlevel) values(1,1,0)";

$db->exec($query);

$db->commit();

}catch(PDOException $ex)
{

	$db->rollBack();
	echo "Virhe, tietokantaa ei voitu alustaa!<br><br>";

	echo $ex->getMessage();
}

?>