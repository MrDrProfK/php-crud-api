<?php
// 	$_DB_CONFIG = parse_ini_file('../../conf/crud_api_db_config.ini');
// 	echo $_DB_CONFIG['user'];
// 	echo '<br>';
// 	echo $_DB_CONFIG['pw'];
// 	echo '<br>';
// 	echo $_DB_CONFIG['dbname'];
// 	echo '<br>';
// 	echo $_SERVER['SERVER_NAME'];
// 	echo '<br>';

function openDBConnection(){
	try {
		$_DB_CONFIG = parse_ini_file('../../conf/crud_api_db_config.ini');
		// establish DB connection
    	$dbh = new PDO("mysql:host={$_SERVER['SERVER_NAME']};dbname={$_DB_CONFIG['dbname']}", 
    				$_DB_CONFIG['user'], $_DB_CONFIG['pw']);
    				
		return $dbh;
// 		$result = $dbh->query("SELECT * from products WHERE name = 'jack'");
// 		if($result){
// 			$_arrayOfRows = $result->fetchAll();
// 			foreach ($_arrayOfRows as $row) {
// 				print_r($row);
// 				echo $row['name'];
// 			}
// 			echo json_encode($_arrayOfRows);
// 		}
		// disconnect from DB
// 		$dbh = null;
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br>";
		exit();
	}
}