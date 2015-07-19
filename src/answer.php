<?php


session_start(); //seession

// Assignment 1 Web Database Appliactions
// By Christopher Noble s3082661
// SP 2 2015

require_once ("php/db.php"); //database info
require_once ("php/formValidate.php"); // validation on user inputs

// if form from search.php has been submited

if(isset($_GET['send']))
{
	// Validate form inputs and then determine query string required for each form input.

	$name = getFormInput('wineName',"", "", "String");
	$regionQuerySection = getFormInput('regionName',"AND region.region_name ='", "'", "String");
	$wineryQuerySection = getFormInput('wineryName',"AND winery.winery_name LIKE '%", "%'", "String");
	$grapeVarQuerySection = getFormInput('grapeVar',"AND wine_variety.types LIKE '%", "%'", "String");
	$stockVarQuerySection = getFormInput('stock',"AND inventory.on_hand >'", "'", "Interger");
	$minCostVarQuerySection = getFormInput('minCost',"AND inventory.cost >='", "'", "Interger");
	$maxCostVarQuerySection = getFormInput('maxCost',"AND inventory.cost <='", "'", "Interger");
	$minYearVarQuerySection = getFormInput('minYear',"AND wine.year >='", "'", "Interger");
	$maxYearVarQuerySection = getFormInput('maxYear',"AND wine.year <='", "'", "Interger");
	$orderedVarQuerySection = getFormInput('ordered'," HAVING SUM(items.qty) >=", " ", "Interger");




	//check for form validation errors if there are some redirect back to search page.
	if (isset($_SESSION['formInputError']))
	{
		header("Location: search.php");

	}
	else
	{
		try
		{
			// Try database connection using PDO, catch any errors

			$dsn = DB_ENGINE.':host='.DB_HOST.';dbname='.DB_NAME;
			$db = new PDO($dsn, DB_USER, DB_PW);

			// (2) Run the query on the winestore through the connection
			$query ="	SELECT wine.wine_name, wine.wine_id, region.region_name, wine_variety.types, winery.winery_name,  GROUP_CONCAT(DISTINCT inventory.cost),  inventory.on_hand AS 'Current Stock', SUM(items.qty) AS 'Times Ordered', sum(items.price),wine.year, SUM(items.qty) AS 'Sold'
						FROM wine
						JOIN winery
						ON winery.winery_id=wine.winery_id
						JOIN region
						ON region.region_id=winery.region_id
						JOIN (	SELECT * , GROUP_CONCAT(DISTINCT variety) AS types
								FROM wine_variety
								NATURAL JOIN grape_variety
								GROUP BY wine_id
							 ) AS wine_variety
						ON wine_variety.wine_id=wine.wine_id
						LEFT JOIN inventory
						ON wine.wine_id=inventory.wine_id
						LEFT JOIN items
						ON items.wine_id=wine.wine_id
						WHERE wine.wine_name LIKE '%".$name."%' ".$wineryQuerySection.$grapeVarQuerySection.$regionQuerySection.$stockVarQuerySection.$maxCostVarQuerySection.$minCostVarQuerySection.$minYearVarQuerySection.$maxYearVarQuerySection."GROUP BY wine.wine_id".$orderedVarQuerySection." ORDER BY wine.wine_name;";

			//$_SESSION['query']=$query;

			// Query database using created query and PDO
			$queryData = $db->query($query);

			// Get all of the results from query and set it to session data
			$result=$queryData->fetchAll();
			$_SESSION['Data']=$result;

			$db = null; 	// close the database connection




		}
		catch(PDOException $e) // catch PDO errors
		{
			echo $e->getMessage();
		}

		// Redirect to results.php
		header("Location: results.php");
	}

}




else
{
	echo "<html><body><p>You have not submitted the correct form</p></body><html>";
}
?>



