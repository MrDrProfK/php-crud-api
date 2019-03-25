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

function validatePrice($name, $value) {

	$refinedString = htmlspecialchars(trim($value));
	
	if (!preg_match("/^[0-9]{1,13}\.[0-9]{2}$/", $refinedString))
		throw new Exception("Invalid value for '{$name}'. '{$name}' must be a positive decimal value, specified to 2 decimal places. If the value specified is less than one, the decimal must be proceeded by a leading zero. The total number of digits may not exceed 15.");
	
	return $refinedString;
}