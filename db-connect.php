<?php
	$_DB_CONFIG = parse_ini_file('../../conf/crud_api_db_config.ini');
	echo $_DB_CONFIG['username'];
	echo '<br>';
	echo $_DB_CONFIG['password'];
	echo '<br>';
	echo $_DB_CONFIG['dbname'];
?>