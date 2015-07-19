

<?php


session_start();  	// start session to retreive searched database data

	// Assignment 1 Web Database Appliactions
	// By Christopher Noble s3082661
	// SP 2 2015

require_once ("php/Template/MiniTemplator.class.php");  	//using mini templator as the template
$t = new MiniTemplator;
$ok = $t->readTemplateFromFile("html/results.html");
if (!$ok) die ("MiniTemplator.readTemplateFromFile failed.");

$queryData = $_SESSION['Data']; //queryied database data from answers.php

foreach ($queryData as $row)
{
	// Get data from secific row

	$wineNane = $row['wine_name'];
	$regionName = $row['region_name'];
	$variety = $row['types'];
	$wineryName = $row['winery_name'];
	$cost = $row['GROUP_CONCAT(DISTINCT inventory.cost)'];
	$year = $row['year'];
	$onHand = $row['Current Stock'];
	$qtySold = $row['Sold'];
	$salesRev = $row['sum(items.price)'];

	// Set variables for use by template and results.html

	$t->setVariable ("wineName",$wineNane);
	$t->setVariable ("region",$regionName);
	$t->setVariable ("variety",$variety);
	$t->setVariable ("wineryName",$wineryName);
	$t->setVariable ("cost",$cost);
	$t->setVariable ("year",$year);
	$t->setVariable ("onHand",$onHand);
	$t->setVariable ("QtySold",$qtySold);
	$t->setVariable ("salesRev",$salesRev);

	// Add block row for template

	$t->addBlock("row");

}

// use mini templator to generate webpage using results html and add vairables from results.php

$t->generateOutput();


?>
