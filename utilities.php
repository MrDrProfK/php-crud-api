<?php

// Created by Aaron Knoll
// Licensed under the GNU GPLv3
// (a copy of which is contained along with this application)

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
	
	if (!preg_match("/^[a-zA-Z0-9]{1,100}$/", $refinedString))
		throw new Exception("Invalid value for 'name'. 'name' must be alphanumeric and can be at most 100 characters.");
		
	return $refinedString;
}

function validatePrice($priceVarName, $value) {

	$refinedString = htmlspecialchars(trim($value));
	
	if (!preg_match("/^[0-9]{1,13}\.[0-9]{2}$/", $refinedString))
		throw new Exception("Invalid value for '{$priceVarName}'. '{$priceVarName}' must be a positive decimal value, specified to 2 decimal places. If the value specified is less than one, the decimal must be proceeded by a leading zero. The total number of digits may not exceed 15.");
	
	return $refinedString;
}

function addIDParamToFilterParams(& $_filterParams) {

	$parentDirName = basename(__DIR__);
	if (preg_match("/\/{$parentDirName}\/products\//i", $_SERVER['REQUEST_URI'])) {
		$_urlSplitByForwardSlash = explode('/', $_SERVER['REQUEST_URI']);
		// Extract id param from URL
		$idParam = explode('?', $_urlSplitByForwardSlash[sizeOf($_urlSplitByForwardSlash) - 1])[0];
		$idParam = trim($idParam);
		if ($idParam != '') {
			$_ids = explode(',', $idParam);

			try {
				foreach ($_ids as $id) {
					// Less sanitization required when compared to values passed by
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
				die;
			}
		}
	}
}