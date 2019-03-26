<?php

// Created by Aaron Knoll
// Licensed under the GNU GPLv3
// (a copy of which is contained along with this application)

require_once 'db-connect.php';
require_once 'utilities.php';

class RowCountDiscrepancyException extends Exception {};

function deleteEntries() {

	$_filterParams = array();
	// If the id param is specified, add it to the filter
	addIDParamToFilterParams($_filterParams);
	
	// Parse query string parameters and store them in $_delete
	$urlParts = parse_url(getenv('REQUEST_URI'));
	parse_str($urlParts['query'], $_delete);
	
	if(isset($_delete['name'])){
		$_names = explode(',', $_delete['name']);
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
	
	if (isset($_delete['price-low'])) {
		try {
			array_push($_filterParams, array('property' => 'price', 'comparator' => '>=', 'value' => validatePrice('price-low', $_delete['price-low'])));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_delete['price-high'])) {
		try {
			array_push($_filterParams, array('property' => 'price', 'comparator' => '<=', 'value' => validatePrice('price-high', $_delete['price-high'])));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	try {
		$_responseSummary = deleteEntriesFromDB($_filterParams);
		http_response_code(200);
		echo json_encode($_responseSummary);
	} catch (RowCountDiscrepancyException $e) {
		http_response_code(500);
		echo 'Caught exception: '. $e->getMessage();
// 		return;
	} catch (Exception $e) {
		http_response_code(400);
		echo 'Caught exception: '. $e->getMessage();
// 		return;
	}
	
}

function deleteEntriesFromDB($_filterParams) {

	if (sizeOf($_filterParams) < 1)
		throw new Exception('Insufficient parameter specification.');
	
	$dbConnection = openDBConnection();
	$numEntries = 0;
	
	$selectionQuery 	= 'SELECT * ';
	$deletionQuery 		= 'DELETE ';
	$queryBuilderStr 	= 'FROM products WHERE ';
	
	for ($i = 0; $i < sizeOf($_filterParams) - 1; $i++) {
		$queryBuilderStr .= $_filterParams[$i]['property'] . ' ';
		$queryBuilderStr .= $_filterParams[$i]['comparator'] . ' ';
		$queryBuilderStr .= $_filterParams[$i]['value'] . ' ';
		$queryBuilderStr .= 'AND ';
	}
	
	$queryBuilderStr .= $_filterParams[$i]['property'] . ' ';
	$queryBuilderStr .= $_filterParams[$i]['comparator'] . ' ';
	$queryBuilderStr .= $_filterParams[$i]['value'];

	$selectionQuery .= $queryBuilderStr;
	$deletionQuery .= $queryBuilderStr;

	$selectionResult = $dbConnection->query($selectionQuery);
	$_entriesToBeDeleted = $selectionResult->fetchAll(PDO::FETCH_ASSOC);
	
	$deletionResult = $dbConnection->exec($deletionQuery);
	
	if (sizeOf($_entriesToBeDeleted) != $deletionResult)
		throw new RowCountDiscrepancyException('Unexpected discrepancy in qualifying row count and actual affected row count!');
	
	return array('deleted_entries' => $deletionResult, 'product' => $_entriesToBeDeleted);

}