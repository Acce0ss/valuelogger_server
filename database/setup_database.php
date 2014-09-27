<?php

include('database_connection.php');

try{

$db->beginTransaction();

foreach($tables as $key => $table)
{
  $query = "DROP TABLE IF EXISTS $table";

  $db->exec($query);
}

$query=	" CREATE TABLE $tables[user] ( " .
	" id INT NOT NULL AUTO_INCREMENT," .
	" username VARCHAR(20) NOT NULL," .
	" authlevel INT NOT NULL DEFAULT 0," .
	" displayname VARCHAR(30) NOT NULL DEFAULT 'Logger'," .
	" joined DATE NOT NULL," .
	" active BOOL NOT NULL DEFAULT 1," .
	" password VARCHAR(40) NOT NULL," .
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
	" value INT NOT NULL," .
	" dataserie_id INT NOT NULL," .
	" adder_id INT NOT NULL," .
	" PRIMARY KEY(id)," .
	" FOREIGN KEY(dataserie_id) REFERENCES $tables[dataserie](id) ON DELETE RESTRICT ON UPDATE CASCADE," .
	" FOREIGN KEY(adder_id) REFERENCES $tables[user](id) ON DELETE RESTRICT ON UPDATE CASCADE" .
	" )";

$db->exec($query);

$query=	"INSERT INTO $tables[user](username, password, displayname, joined, authlevel)  " .
	"  values( 'asser'," .
	"      SHA('asser')," .
	"          'Asser'," .
	"          DATE(NOW()), 100)";

$db->exec($query);

$db->commit();

}catch(PDOException $ex)
{

	$db->rollBack();
	echo "Virhe, tietokantaa ei voitu alustaa!<br><br>";

	echo $ex->getMessage();
}

?>