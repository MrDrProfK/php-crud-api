<?php
header("Access-Control-Allow-Origin: *");
// Perform appropriate CRUD operation based on HTTP request method
switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		// TODO: createEntries();
		try {
			for ($i = 0; $i < $_POST['entries']; $i++) {
				
				$_PRODUCT = $_POST['product'][$i];
				$name = validateParams('name', $_PRODUCT['name']);
				$price = validateParams('price', $_PRODUCT['price']);
				
				http_response_code(200);
				echo $name . ' ';
				echo money_format('$%i',floatval($price));
				echo "\n";
			}	
// 			foreach ($_POST['product'] as $_PRODUCT) {
// 				echo $_PRODUCT['name'];
// 				echo "\n";
// 				echo $_PRODUCT['price'];
// 			}

// 			// TODO: createEntries();
		} catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n";
		}
		break;
	case 'GET':
		// TODO: readEntries();
		break;
	case 'PUT';
		// TODO: updateEntries();
		break;
	case 'DELETE';
		// TODO: deleteEntries();
		break;
	default:
		echo 'Invalid request method.';
}

function validateParams($param, $value) {
	if (empty($value))
		throw new Exception("Missing value for parameter '{$param}'.");
	
	$refinedString = htmlspecialchars(trim($value));
	
	if (empty($refinedString))
		throw new Exception("Invalid value for parameter '{$param}'.");
		
	return $refinedString;
}

?>