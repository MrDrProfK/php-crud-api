<?php

require_once 'db-connect.php';

function createEntries() {
	try {
		$_processedInput = array();
		for ($i = 0; $i < $_POST['entries']; $i++) {
			$_product = $_POST['product'][$i];
			$name = validateParam('name', $_product['name']);
			$price = validateParam('price', $_product['price']);
			
			$_processedInput[$i] = array('name' => $name, 'price' => $price);
		}		
		for ($i = 0; $i < $_POST['entries']; $i++) {
			$_processedInput[$i];
		}
		
		$_responseSummary = insertEntriesIntoDB($_processedInput);
		http_response_code(200);
		echo json_encode($_responseSummary);
				
	} catch (Exception $e) {
		http_response_code(400);
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function validateParam($param, $value) {
	if ($value === '')
		throw new Exception("Missing value for parameter '{$param}'.");
	
	$refinedString = htmlspecialchars(trim($value));
	
// 	if (empty($refinedString))
// 		throw new Exception("Invalid value for parameter '{$param}'.");
		
	return $refinedString;
}

function insertEntriesIntoDB($_processedInput) {
	$dbConnection = openDBConnection();
	$_insertedEntries = array();
	$numEntries = 0;
	foreach ($_processedInput as $singleEntry) {
		$name = $singleEntry['name'];
		$price = $singleEntry['price'];
		
		$insertionQuery = "INSERT INTO products (name, price) VALUES ('{$name}','{$price}')";
		$insertionResult = $dbConnection->exec($insertionQuery);
		
		if ($insertionResult) {
			$lastID = $dbConnection->lastInsertID();
			$selectionQuery = "SELECT * FROM products WHERE id='{$lastID}'";
			$selectionResult = $dbConnection->query($selectionQuery);
			$_insertedEntries[$numEntries++] = $selectionResult->fetch(PDO::FETCH_ASSOC);
		}
	}
	return array('inserted_entries' => $numEntries, 'product' => $_insertedEntries);
	
}