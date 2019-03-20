<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
require_once 'create.php';
require_once 'read.php';
require_once 'update.php';
require_once 'delete.php';

// Perform appropriate CRUD operation based on HTTP request method
switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		createEntries();
		break;
	case 'GET':
		readEntries();
		break;
	case 'PUT':
		updateEntries();
		break;
	case 'DELETE':
		deleteEntries();
		break;
	default:
		echo json_encode('Invalid request method.');
}