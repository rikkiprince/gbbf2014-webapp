<?php

require_once("../../HTTP.php");
require_once("../../HTML.php");

$USE_CACHE = false;


?>

<html>
	<head>
		<title>Scraping of 2014 GBBF site</title>
		<style>
			textarea {
				width: 40em;
				max-width: 100%;
				height: 10em;
				font-family: monospace;
			}
		</style>
	</head>
	<body>

<?php
date_default_timezone_set("Europe/London");
echo "<p>Last updated: ".date("D jS M Y")."</p>";
?>

<p>To make this app work, you need the data from

<?php

$beerDB = array();
$globalCounter = 2000;



//$beerXML = http_get("http://beer.gbbf.org.uk/", $USE_CACHE);
$beerURL = "http://beer.gbbf.org.uk/";
$beerXPath = getXPathForHTML($beerURL);

/*-- GET DESCRIPTIONS --*/
$beerDescriptions = array();
$dialogs = $beerXPath->evaluate("//div[contains(concat(' ', normalize-space(@class), ' '), ' modal ')]");
foreach($dialogs as $dialog) {
	$id = $dialog->getAttribute("id");
	$description = $beerXPath->evaluate(".//div[contains(concat(' ', normalize-space(@class), ' '), ' modal-body ')]", $dialog);
	
	$beerDescriptions[$id] = trim($description->item(0)->textContent);
}

/******************
	REAL ALES
*******************/

/*-- GET HEADINGS --*/
$headings = array();
$realAleHeadings = $beerXPath->evaluate("//h3[contains(., 'Real Ales')]/../..//table/thead/tr/th");
foreach($realAleHeadings as $heading) {
	$headings[] = trim($heading->textContent);
}
//HTML::print_r($headings);

/*-- GET BEERS --*/
$realAleRows = $beerXPath->evaluate("//h3[contains(., 'Real Ales')]/../..//table/tbody/form/tr");
//HTML::print_r($realAleRows);
foreach($realAleRows as $r) {
	$beer = array();
	$tempBeer = array();

	$id = $beerXPath->evaluate("td/a/@data-target", $r);
	$beer["ID"] = str_replace("#", "", trim($id->item(0)->textContent));

	$beer["Description"] = $beerDescriptions[$beer["ID"]];

	$tds = $beerXPath->evaluate("td", $r);
	//HTML::print_r($tds);
	$c = 0;
	foreach ($tds as $td) {
		if(!array_key_exists($headings[$c], $beer))
			$beer[$headings[$c]] = trim($td->textContent);
		$c++;
	}

	// ICON
	$imgURLs = $beerXPath->evaluate("td/img/@src", $r);
	foreach($imgURLs as $url) {
		$u = $url->textContent;
		$beer["Icon"] = $u;
	}

	/* MANUALLY SET */
	// COUNTRY
	$beer["Country"] = "Great Britain";
	// DISPENSE METHOD
	$beer["Dispense Method"] = "Draught";


	$tempBeer['name'] = $beer['Beer Name'];
	$tempBeer['brewery'] = $beer['Brewery Name'];
	$tempBeer['abv'] = $beer['ABV%'];
	$tempBeer['style'] = $beer['Style'];
	$tempBeer['colour'] = $beer['Colour'];
	$tempBeer['bar'] = $beer['Bar'];
	$tempBeer['country'] = $beer['Country'];
	$tempBeer['dispense'] = $beer['Dispense Method'];
	$tempBeer['icon'] = $beer['Icon'];
	$tempBeer['id'] = $beer['ID'];
	$tempBeer['description'] = $beer['Description'];

	$beerDB[] = $tempBeer;
}


/******************
	FOREIGN BEERS
*******************/

/*-- GET HEADINGS --*/
$headings = array();
$realAleHeadings = $beerXPath->evaluate("//h3[contains(., 'Foreign Beers')]/../..//table/thead/tr/th");
foreach($realAleHeadings as $heading) {
	$headings[] = trim($heading->textContent);
}
//HTML::print_r($headings);

/*-- GET BEERS --*/
$realAleRows = $beerXPath->evaluate("//h3[contains(., 'Foreign Beers')]/../..//table/tbody/form/tr");
//HTML::print_r($realAleRows);
foreach($realAleRows as $r) {
	$beer = array();
	$tempBeer = array();

	$id = $beerXPath->evaluate("td/a/@data-target", $r);
	$beer["ID"] = str_replace("#", "", trim($id->item(0)->textContent));

	$beer["Description"] = $beerDescriptions[$beer["ID"]];

	$tds = $beerXPath->evaluate("td", $r);
	//HTML::print_r($tds);
	$c = 0;
	foreach ($tds as $td) {
		if(!array_key_exists($headings[$c], $beer))
			$beer[$headings[$c]] = trim($td->textContent);
		$c++;
	}

	// ICON
	$imgURLs = $beerXPath->evaluate("td/img/@src", $r);
	foreach($imgURLs as $url) {
		$u = $url->textContent;
		$beer["Icon"] = $u;
	}

	/* MANUALLY SET */
	// STYLE
	$beer["Style"] = "Unknown";
	// COLOUR
	$beer["Colour"] = "Unknown";


	$tempBeer['name'] = $beer['Beer Name'];
	$tempBeer['brewery'] = $beer['Brewery Name'];
	$tempBeer['abv'] = $beer['ABV%'];
	$tempBeer['style'] = $beer['Style'];
	$tempBeer['colour'] = $beer['Colour'];
	$tempBeer['bar'] = $beer['Bar'];
	$tempBeer['country'] = $beer['Country'];
	$tempBeer['dispense'] = $beer['Dispense Method'];
	$tempBeer['icon'] = $beer['Icon'];
	$tempBeer['id'] = $beer['ID'];
	$tempBeer['description'] = $beer['Description'];

	$beerDB[] = $tempBeer;
}


/******************
	CIDERS AND PERRIES
*******************/

/*-- GET HEADINGS --*/
$headings = array();
$realAleHeadings = $beerXPath->evaluate("//h3[contains(., 'Ciders and Perries')]/../..//table/thead/tr/th");
foreach($realAleHeadings as $heading) {
	$headings[] = trim($heading->textContent);
}
//HTML::print_r($headings);

/*-- GET BEERS --*/
$realAleRows = $beerXPath->evaluate("//h3[contains(., 'Ciders and Perries')]/../..//table/tbody/form/tr");
//HTML::print_r($realAleRows);
foreach($realAleRows as $r) {
	$beer = array();
	$tempBeer = array();

	$id = $beerXPath->evaluate("td/a/@data-target", $r);
	if($id->length > 0) {
		$beer["ID"] = str_replace("#", "", trim($id->item(0)->textContent));

		$beer["Description"] = $beerDescriptions[$beer["ID"]];
	}

	$tds = $beerXPath->evaluate("td", $r);
	//HTML::print_r($tds);
	$c = 0;
	foreach ($tds as $td) {
		if(!array_key_exists($headings[$c], $beer))
			$beer[$headings[$c]] = trim($td->textContent);
		$c++;
	}

	// STYLE
	$imgURLs = $beerXPath->evaluate("td/img/@src", $r);
	foreach($imgURLs as $url) {
		$u = $url->textContent;
		if($u == "http://beer.gbbf.org.uk/cider/cider.jpg")
			$beer["Style"] = "Cider";
		elseif($u == "http://beer.gbbf.org.uk/cider/perry.jpg")
			$beer["Style"] = "Perry";
		elseif(strripos($u, "cider"))
			$beer["Style"] = "Cider";
		elseif(strripos($u, "perry"))
			$beer["Style"] = "Perry";
		else
			$beer["Style"] = "Unknown";

		$beer["Icon"] = $u;
	}

	/* MANUALLY SET */
	$beer["Beer Name"] = $beer["Name"];
	// BREWERY NAME
	$beer["Brewery Name"] = $beer["Producer"];
	// COUNTRY
	$beer["Country"] = "West Country";
	// COLOUR
	$beer["Colour"] = "Unknown";
	// DISPENSE METHOD
	$beer["Dispense Method"] = "Unknown";
	// ABV
	$beer["ABV%"] = "Unknown";
	// ID
	$beer["ID"] = "Unique".(++$globalCounter);
	// Description
	$beer["Description"] = "";


	$tempBeer['name'] = $beer['Beer Name'];
	$tempBeer['brewery'] = $beer['Brewery Name'];
	$tempBeer['abv'] = $beer['ABV%'];
	$tempBeer['style'] = $beer['Style'];
	$tempBeer['colour'] = $beer['Colour'];
	$tempBeer['bar'] = $beer['Bar'];
	$tempBeer['country'] = $beer['Country'];
	$tempBeer['dispense'] = $beer['Dispense Method'];
	$tempBeer['icon'] = $beer['Icon'];
	$tempBeer['id'] = $beer['ID'];
	$tempBeer['description'] = $beer['Description'];

	$beerDB[] = $tempBeer;
}


/** PRINT ALL BEERS **/
echo HTML::p("Save to ".HTML::span("gbbf2014.json"));
echo HTML::textarea(json_encode($beerDB));
//HTML::print_r($beerDB);


function getall($key) {
	global $beerDB;
	$breweries = array();
	foreach($beerDB as $beer) {
		$breweries[] = $beer[$key];
	}
	$breweries = array_unique($breweries);
	return json_encode(array_values($breweries));
}
/** GENERATE LIST OF ALL BREWERIES **/
echo HTML::p("Save to ".HTML::span("brewery.json"));
echo HTML::textarea(getall('brewery'));
/** GENERATE LIST OF ALL BARS **/
echo HTML::p("Save to ".HTML::span("bar.json"));
echo HTML::textarea(getall('bar'));
/** GENERATE LIST OF ALL COUNTRIES **/
echo HTML::p("Save to ".HTML::span("country.json"));
echo HTML::textarea(getall('country'));
/** GENERATE LIST OF ALL STYLES **/
echo HTML::p("Save to ".HTML::span("style.json"));
echo HTML::textarea(getall('style'));

exit;

?>

</body>
</html>

<?php


function getXPathForHTML($url, $print=false)
{
	global $USE_CACHE;
	
	$html = http_get($url, $USE_CACHE);
	if($print) {
		echo "<h3>$url</h3>";
		HTML::print_r(htmlentities($html));
	}
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	return new DOMXPath($dom);
}
function getXPathForXML($url)
{
	global $USE_CACHE;
	
	$html = http_get($url, $USE_CACHE);
	$dom = new DOMDocument();
	@$dom->loadXML($html);
	return new DOMXPath($dom);
}

?>