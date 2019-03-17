<html>
<head></head>
<body>
	<form method="post">
		Product name:<br>
		<input type="text" name="name"><br>
		Price:<br>
		<input type="text" name="price"><br>
		<input type="submit">
	</form>
</body>
</html>

<?php
// echo $_SERVER['DOCUMENT_ROOT'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	try {
		$name = validateParams('name', $_POST['name']);
		$price = validateParams('price', $_POST['price']);
		http_response_code(200);
		echo $name;
		echo '<br>';
		echo money_format('$%i',floatval($price));
	} catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
}

function validateParams($param, $value) {
	if (empty($value))
		throw new Exception("Missing value for parameter '{$param}'.");
		
	return htmlspecialchars(trim($value));
}

?>