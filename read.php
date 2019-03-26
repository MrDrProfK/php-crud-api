<?php

// Created by Aaron Knoll
// Licensed under the GNU GPLv3
// (a copy of which is contained along with this application)

require_once 'db-connect.php';
require_once 'utilities.php';

function readEntries() {

	$_filterParams = array();
	// If the id param is specified, add it to the filter
	addIDParamToFilterParams($_filterParams);
	
	if(isset($_GET['name'])){
		$_names = explode(',', $_GET['name']);
		try {
			$sanitizedNames = '';
			for ($i = 0; $i < sizeOf($_names) - 1; $i++) {
				$validatedName = validateName($_names[$i]);
				$sanitizedNames .= "'{$validatedName}'" . ',';
			}
			$validatedName = validateName($_names[$i]);
			$sanitizedNames .= "'{$validatedName}'";
			
			array_push($_filterParams, array('property' => 'name', 'comparator' => 'IN',
												'value' => '(' . $sanitizedNames . ')'));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_GET['price-low'])) {
		try {
			array_push($_filterParams, array('property' => 'price', 'comparator' => '>=', 'value' => validatePrice('price-low', $_GET['price-low'])));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_GET['price-high'])) {
		try {
			array_push($_filterParams, array('property' => 'price', 'comparator' => '<=', 'value' => validatePrice('price-high', $_GET['price-high'])));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	$_responseSummary = readEntriesFromDB($_filterParams);
	http_response_code(200);
	echo json_encode($_responseSummary);
}

function readEntriesFromDB($_filterParams) {

	$dbConnection = openDBConnection();
	$numEntries = 0;
	$selectionQuery = 'SELECT * FROM products';
	if (sizeOf($_filterParams) > 0) {
		$selectionQuery .= " WHERE ";
	
		for ($i = 0; $i < sizeOf($_filterParams) - 1; $i++) {
			$selectionQuery .= $_filterParams[$i]['property'] . ' ';
			$selectionQuery .= $_filterParams[$i]['comparator'] . ' ';
			$selectionQuery .= $_filterParams[$i]['value'] . ' ';
			$selectionQuery .= 'AND ';
		}
		$selectionQuery .= $_filterParams[$i]['property'] . ' ';
		$selectionQuery .= $_filterParams[$i]['comparator'] . ' ';
		$selectionQuery .= $_filterParams[$i]['value'];
	}
	// Order by id (can change later to give client more flexibility)
	$selectionQuery .= ' ORDER BY id ASC';

	$selectionResult = $dbConnection->query($selectionQuery);
	$_fetchedEntries = $selectionResult->fetchAll(PDO::FETCH_ASSOC);
	
	return array('fetched_entries' => sizeOf($_fetchedEntries), 'product' => $_fetchedEntries);

}