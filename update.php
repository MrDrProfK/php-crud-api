<?php

// Created by Aaron Knoll
// Licensed under the GNU GPLv3
// (a copy of which is contained along with this application)

require_once 'db-connect.php';
require_once 'utilities.php';

function updateEntries() {
	parse_str(file_get_contents("php://input"), $_put);
	
	try {
		$_processedInput = array();
		for ($i = 0; $i < $_put['entries']; $i++) {
			$_product = $_put['product'][$i];
			$id = validateID($_product['id']);
			$name = validateName($_product['name']);
			$price = validatePrice('price', $_product['price']);
			
			$_processedInput[$i] = array('id' => $id, 'name' => $name, 'price' => $price);
		}

		$_responseSummary = updateEntriesInDB($_processedInput);
		http_response_code(200);
		echo json_encode($_responseSummary);
				
	} catch (Exception $e) {
		http_response_code(400);
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function updateEntriesInDB($_processedInput) {

	$dbConnection = openDBConnection();
	$_updatedEntries = array();
	$numEntries = 0;
	foreach ($_processedInput as $singleEntry) {
		$id = $singleEntry['id'];
		$name = $singleEntry['name'];
		$price = $singleEntry['price'];
		
		$updateQuery = "UPDATE products SET name = '{$name}', price = '{$price}' WHERE id = {$id}";
		$updateResult = $dbConnection->exec($updateQuery);

		if ($updateResult) {
			$selectionQuery = "SELECT * FROM products WHERE id = {$id}";
			$selectionResult = $dbConnection->query($selectionQuery);
			$_updatedEntries[$numEntries++] = $selectionResult->fetch(PDO::FETCH_ASSOC);
		}
	}
	return array('updated_entries' => $numEntries, 'product' => $_updatedEntries);

}