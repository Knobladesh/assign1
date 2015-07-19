

<?php
session_start();

	// Assignment 1 Web Database Appliactions
	// By Christopher Noble s3082661
	// SP 2 2015

require_once ("php/Template/MiniTemplator.class.php");
require_once ("php/db.php");
$t = new MiniTemplator;
$ok = $t->readTemplateFromFile("html/search.html");
if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

// Check for error messages. Should only occur when form has been submitted with incorrect input
// and has been redirected back to search page with sessionerrors set.
foreach ($_SESSION['formInputError'] as $key => $errMessage)
{

	$t->setVariable($key,$errMessage);
	$t->setVariable("display$key","has-error");

}

// unset sessions so that input validation on form submit can start afresh
session_unset();

try
{
	// Try database connection using PDO, catch any errors

	$dsn = DB_ENGINE.':host='.DB_HOST.';dbname='.DB_NAME;
	$db = new PDO($dsn, DB_USER, DB_PW);


	// Query and forloop for wine region dropdown values
	$regionQuery = "select region_name from region";

	foreach ($db->query($regionQuery) as $row)
	{
		$regionName = $row['region_name'];
		$t->setVariable ("regionName",$regionName);
		$t->addBlock ("region");
	}

	// Query and forloop for wine grape variety dropdown values
	$grapeQuery = "select variety from grape_variety";
	foreach ($db->query($grapeQuery) as $row)
	{
		$grapeVarName = $row['variety'];
		$t->setVariable("grapeVarName", $grapeVarName);
		$t->addBlock("grapeVar");
	}

	// Query and forloop to determine max year
	$queryMaxYear = "SELECT MAX(year) FROM wine";
	foreach ($db->query($queryMaxYear) as $row)
	{
		$maxYear = $row['MAX(year)'];
		$t->setVariable ("maxYear",$maxYear);

	}

	// Query and forloop to determine min year
	$resultMinYear = "SELECT MIN(year) FROM wine";
	foreach ($db->query($resultMinYear) as $row)
	{
		$minYear = $row['MIN(year)'];
		$t->setVariable ("minYear",$minYear);

	}

	$db = null; 	// close the database connection

}
// Catch errors when setting up database connection
catch(PDOException $e)
{
	echo $e->getMessage();
}



// mini templator generateing output using asociated HTML page
$t->generateOutput();



?>
