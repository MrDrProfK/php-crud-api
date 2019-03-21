<?php

require_once 'db-connect.php';
// readEntries();
function readEntries() {

	$_arr = array();
	$_urlSplitByForwardSlash = explode('/', $_SERVER['REQUEST_URI']);
	if (sizeOf($_urlSplitByForwardSlash) > 3) {
		// extract id param from url
		$idParam = explode('?', $_urlSplitByForwardSlash[3])[0];
		$idParam = trim($idParam);
		if ($idParam != '') {
			$_ids = explode(',', $idParam);
		
			try {
				foreach ($_ids as $id) {
					validateID($id);
				}		
				$_arr['ids'] = $_ids;
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
			foreach ($_names as $name) {
				validateName('name', $name);
			}
			$_arr['names'] = $_names;
		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_GET['price-low'])) {
		try {
			$_arr['price-low'] = validatePriceBound('price-low', $_GET['price-low']);
		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}
	
	if (isset($_GET['price-high'])) {
		try {
			$_arr['price-high'] = validatePriceBound('price-high', $_GET['price-high']);
		} catch (Exception $e) {
			http_response_code(400);
			echo 'Caught exception: '. $e->getMessage();
			return;
		}
	}

	http_response_code(200);
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