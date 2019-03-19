<?php

function updateEntries() {
	parse_str(file_get_contents("php://input"), $_PUT);
	echo json_encode($_PUT);
// 	echo json_encode($_PUT['product'][1]);
}