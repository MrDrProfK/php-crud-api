<?php

require_once 'db-connect.php';

function createEntries() {
	try {
		$_processedInput = array();
		for ($i = 0; $i < $_POST['entries']; $i++) {
			$_product = $_POST['product'][$i];
			$name = validateParams('name', $_product['name']);
			$price = validateParams('price', $_product['price']);
			
			$_processedInput[$i] = array('name' => $name, 'price' => $price);
		}		
		for ($i = 0; $i < $_POST['entries']; $i++) {
			$_processedInput[$i];
		}
		
		$_responseSummary = insertEntriesIntoDB($_processedInput);
		http_response_code(200);
		echo json_encode($_responseSummary);
				
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