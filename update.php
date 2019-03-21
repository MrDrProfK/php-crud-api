<?php

require_once 'db-connect.php';

function updateEntries() {
	parse_str(file_get_contents("php://input"), $_put);
	echo json_encode($_put);
}