<?php

function createEntries() {
	try {
// 			echo $_SERVER["CONTENT_TYPE"] . "\n";
		for ($i = 0; $i < $_POST['entries']; $i++) {
			
			$_PRODUCT = $_POST['product'][$i];
			$name = validateParams('name', $_PRODUCT['name']);
			$price = validateParams('price', $_PRODUCT['price']);				
		}
		http_response_code(200);
		echo json_encode($_POST);
// 			echo json_encode($_POST, JSON_FORCE_OBJECT);
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function validateParams($param, $value) {
	if (empty($value))
		throw new Exception("Missing value for parameter '{$param}'.");
	
	$refinedString = htmlspecialchars(trim($value));
	
	if (empty($refinedString))
		throw new Exception("Invalid value for parameter '{$param}'.");
		
	return $refinedString;
}