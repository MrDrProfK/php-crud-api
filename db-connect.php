<?php

// Created by Aaron Knoll
// Licensed under the GNU GPLv3
// (a copy of which is contained along with this application)

function openDBConnection() {

	try {
		$_DB_CONFIG = parse_ini_file('../../conf/crud_api_db_config.ini');
		// establish DB connection
    	$dbh = new PDO("mysql:host={$_SERVER['SERVER_NAME']};dbname={$_DB_CONFIG['dbname']}", 
    				$_DB_CONFIG['user'], $_DB_CONFIG['pw']);
    				
		return $dbh;
		
	} catch (PDOException $e) {
		http_response_code(500);
		echo "Error!: " . $e->getMessage() . "<br>";
		die;
	}
	
}