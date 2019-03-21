<?php

require_once 'db-connect.php';

function deleteEntries() {
	$id = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];

	$urlParts = parse_url(getenv('REQUEST_URI'));
	parse_str($urlParts['query'], $_delete);
	
	echo json_encode(array('id' => $id, 'name' => $_delete['name'],
			'priceLow' => $_delete['price-low'], 'priceHigh' => $_delete['price-high']));
}