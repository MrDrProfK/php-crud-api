<?php

function openDBConnection() {

	try {
		$_DB_CONFIG = parse_ini_file('../../conf/crud_api_db_config.ini');
		// establish DB connection
    	$dbh = new PDO("mysql:host={$_SERVER['SERVER_NAME']};dbname={$_DB_CONFIG['dbname']}", 
    				$_DB_CONFIG['user'], $_DB_CONFIG['pw']);
    				
		return $dbh;
		
	} catch (PDOException $e) {
		echo "Error!: " . $e->getMessage() . "<br>";
		exit();
	}
	
}