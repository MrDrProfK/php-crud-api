<?php

function readEntries() {
	// extract id param from url
	$id = explode('?', explode('/', $_SERVER['REQUEST_URI'])[3])[0];
	echo json_encode(array('id' => $id, 'name' => $_GET['name'],
			'priceLow' => $_GET['priceLow'], 'priceHigh' => $_GET['priceHigh']));
}