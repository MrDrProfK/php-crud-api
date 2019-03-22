<?php

require_once 'db-connect.php';
require_once 'utilities.php';

function createEntries() {
	try {
		$_processedInput = array();
		for ($i = 0; $i < $_POST['entries']; $i++) {
			$_product = $_POST['product'][$i];
			$name = validateName($_product['name']);
			$price = validatePrice('price', $_product['price']);
			
			$_processedInput[$i] = array('name' => $name, 'price' => $price);
		}		
		
		$_responseSummary = insertEntriesIntoDB($_processedInput);
		http_response_code(200);
		echo json_encode($_responseSummary);
				
	} catch (Exception $e) {
		http_response_code(400);
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
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