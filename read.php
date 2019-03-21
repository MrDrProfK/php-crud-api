<?php

require_once 'db-connect.php';
// For debugging
// readEntries();
function readEntries() {

	$_filterParams = array();
	$_urlSplitByForwardSlash = explode('/', $_SERVER['REQUEST_URI']);
	if (sizeOf($_urlSplitByForwardSlash) > 3) {
		// extract id param from url
		$idParam = explode('?', $_urlSplitByForwardSlash[3])[0];
		$idParam = trim($idParam);
		if ($idParam != '') {
			$_ids = explode(',', $idParam);
		
			try {
				foreach ($_ids as $id) {
					// Less sanitization required in compared to values passed by
					// query string
					validateID($id);
				}
				// No need to re-assemble the id list, as url encoded whitespace should
				// not pass prior validation.
				array_push($_filterParams, array('property' => 'id', 'comparator' => 'IN',
													'value' => '(' . $idParam . ')'));

			} catch (Exception $e) {
				http_response_code(400);
				echo 'Caught exception: '. $e->getMessage();
				return;
			}
		}
	}
	
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
			array_push($_filterParams, array('property' => 'price', 'comparator' => '>=', 'value' => validatePriceBound('price-low', $_GET['price-low'])));

		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_GET['price-high'])) {
		try {
			array_push($_filterParams, array('property' => 'price', 'comparator' => '<=', 'value' => validatePriceBound('price-high', $_GET['price-high'])));

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
// 	$_fetchedEntries = array();
	$numEntries = 0;
// 	"id='{$lastID}'"
	$selectionQuery = 'SELECT * FROM products';
	if (sizeOf($_filterParams) > 0) {
		$selectionQuery .= " WHERE ";
		echo var_dump($_filterParams);
	
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
	echo "\n" . $selectionQuery . "\n";

	$selectionResult = $dbConnection->query($selectionQuery);
	$_fetchedEntries = $selectionResult->fetchAll(PDO::FETCH_ASSOC);
	// echo var_dump($_fetchedEntries);
	
	return array('fetched_entries' => sizeOf($_fetchedEntries), 'product' => $_fetchedEntries);

}

function validateID($id) {

	$refinedString = htmlspecialchars(trim($id));

	if (filter_var($refinedString, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))))
		return $refinedString;

	throw new Exception("Invalid value for 'id'. An 'id' must be specified by an int greater than 0.");
}

function validateName($value) {
	if (trim($value) === '')
		throw new Exception("Missing value for parameter 'name'.");
	
	$refinedString = htmlspecialchars(trim($value));
	
	return $refinedString;
}

function validatePriceBound($name, $value) {

	$refinedString = htmlspecialchars(trim($value));
	
	if (preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $refinedString))
		return $refinedString;
	
	throw new Exception("Invalid value for '{$name}'.");
}