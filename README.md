#Overview

##Motivation
- Demonstrate an understanding of core back end fundamentals in PHP, when interfacing with a database.

##Mission (Accomplished)
- Design and implement a small API that allows a client to indirectly interface with a MySQL database.

##Main Data Entity Specification
- Name: product
- Properties:
  - id: positive integer, assigned automatically
  - name: alphanumeric, at most 100 characters
  - price: positive decimal, with 2 decimal places and up to 15 total digits

##Core Capabilities
1. Create, retrieve (read), update, and delete records (CRUD).
2. Retrieve a list of products, filtered optionally by name.
3. Appropriate exception handling and sensible error messages.

##Additional Capabilities
1. Retrieve a list of products, filtered optionally by id, price-low, and price-high.
2. Delete records, filtered by id, name, price-low, or price-high (or any combination of these properties).

##Dependencies
- Apache
- MySQL
- PHP 7

##Deployment
Create a database table in MySQL called products with the following SQL schema:

	CREATE TABLE `products` (
	 `id` int(11) NOT NULL AUTO_INCREMENT,
	 `name` varchar(100) NOT NULL,
	 `price` decimal(15,2) NOT NULL,
	 PRIMARY KEY (`id`),
	 UNIQUE KEY `unique_index` (`name`,`price`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1

##Encoding

- Request Body: application/x-www-form-urlencoded<br>
- Response Body: application/json

#Usage

###Create Records

Request

`POST /api/products/`

####Request Body:

	{
		"entries": 2,
		"product": [
			{
				"name": "widget1",
				"price": "3.29"
			},
			{
				"name": "widget2",
				"price": "4.37"
			}
		]
	}

- *entries*: number of products to be inserted into the database<br>
- *product*: array of objects specifying the name and price parameters for products to be inserted into the database

####Response:

	{
		"inserted_entries": 1,
		"product": [
			{
				"id": "419",
				"name": "widget2",
				"price": "4.37"
			}
		]
	}

- *inserted_entries*: number of products inserted into the database<br>
- *product*: array of objects specifying the id, name, and price parameters for products inserted into the database

###Retrieve Records

`GET /api/products/{id}?name=NAME&price-low=PRICE-LOW&price-high=PRICE-HIGH`

- *id*: positive integer (or list of integers)<br>
- *name*: alphanumeric string (or list of strings)<br>
- *price-low*: positive decimal value specified to 2 decimal places<br>
- *price-high*: positive decimal value specified to 2 decimal places

**Note**: All parameters (path and query) are optional. If no parameters are specified, all products will be returned.

####Response:

	{
		"fetched_entries": 1,
		"product": [
			{
				"id": "418",
				"name": "widget1",
				"price": "3.29"
			}
		]
	}

- *fetched_entries*: number of products fetched from the database<br>
- *product*: array of objects specifying the id, name, and price parameters for products fetched from the database

###Update Records

`PUT /api/products/`

####Request Body:

	{
		"entries": 2,
		"product": [
			{
				"id": "366",
				"name": "widget5",
				"price": "2.24"
			},
			{
				"id": "1",
				"name": "widget6",
				"price": "3.33"
			}
		]
	}

- *entries*: number of products to be updated in the database<br>
- *product*: array of objects specifying the id, name, and price parameters for products to be updated in the database

####Response:

	{
		"updated_entries": 2,
		"product": [
			{
				"id": "366",
				"name": "widget5",
				"price": "2.24"
			},
			{
				"id": "1",
				"name": "widget6",
				"price": "3.33"
			}
		]
	}

- *updated_entries*: number of products updated in the database<br>
- *product*: array of objects specifying the id, name, and price parameters for products updated in the database

###Delete Records

`DELETE /api/products/{id}?name=NAME&price-low=PRICE-LOW&price-high=PRICE-HIGH`

- *id*: positive integer (or list of integers)<br>
- *name*: alphanumeric string (or list of strings)<br>
- *price-low*: positive decimal value specified to 2 decimal places<br>
- *price-high*: positive decimal value specified to 2 decimal places

**NOTE**: At least one parameter (path or query) must be specified. Additionally, other parameters may be optionally specified.

####Response:

	{
		"deleted_entries": 1,
		"product": [
			{
				"id": "419",
				"name": "widget2",
				"price": "4.37"
			}
		]
	}

- *deleted_entries*: number of products deleted from the database<br>
- *product*: array of objects specifying the id, name, and price parameters for products deleted from the database

#Status Codes and Errors

Code  	| Description
----- 	| -------------
200   	| OK
400   	| Bad Request
500	  	| Internal Server Error

#Additional Considerations

1. Query parameter names are case-sensitive, while their client supplied values are not.
2. When providing multiple values for a given path or query parameters (which supports it), separate the values with a comma (`,`).
3. Duplicate names are acceptable, but not duplicate entries (the combination of a name and price must be unique).