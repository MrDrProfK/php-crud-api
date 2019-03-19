<?php

function deleteEntries() {
	$id = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];

	$urlParts = parse_url(getenv('REQUEST_URI'));
	parse_str($urlParts['query'], $_DELETE);
	
	echo json_encode(array('id' => $id, 'name' => $_DELETE['name'],
			'priceLow' => $_DELETE['priceLow'], 'priceHigh' => $_DELETE['priceHigh']));
}