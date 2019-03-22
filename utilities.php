<?php

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

function validatePrice($name, $value) {

	$refinedString = htmlspecialchars(trim($value));
	
	if (preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $refinedString))
		return $refinedString;
	
	throw new Exception("Invalid value for '{$name}'.");
}