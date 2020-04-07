<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
	// Just return headers if we've been passed an OPTIONS request.
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=576" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet" />
<style>
body
{
    background-color: black;
    color: white;
    font-family: 'Jura', sans-serif;
    text-align: center;
    width: 576px;
    margin: auto;
}
#header
{
    position:relative;
    height: 510px;
}
#intro
{
    width: 510px;
    padding-left: 28px;
    padding-right: 28px;
    position:absolute;
    bottom:0;
}
.roundrect
{
    background-color: white;
    color: black;
    width: 420px;
    border-radius: 25px;
    border: 2px solid;
    padding: 20px;
    margin-top: 20px;
    margin-left: auto;
    margin-right: auto;
}
.dead
{
    background-color: #c0c0c0;
}

</style>
</head>
<body>
<div id='container'>
<div id='header'>
<div id='anim'>

<?php

// By default, the graphic links to the root of the website.
$url = "/";

// Output the graphic header, and the intro div.
echo "<a href='".$url."'>\n<img src='/poxemon.gif' />\n</a>\n</div>\n";
echo "<div id='intro'>";

// Report all PHP errors
error_reporting(-1);

// By default we've no infections, and we don't know the vaccination status.
$infections = 0;
$deaths = 0;
$stayedhome = false;


// We must be explicity passed a card list as a parameter
if ( isset($_REQUEST['cards']) && !empty($_REQUEST['cards']))
{
	// Build the deck from the cards param passed in.
	// Any elements beyond 100 are left in the 101th entry.
	$cards = explode(',', $_REQUEST['cards'], 101);
	$infections = count($cards);

    // Have we been explicity passed stayed-at-home status?
    if ( isset($_REQUEST['stayedhome']) && !empty($_REQUEST['stayedhome']))
    {
        $stayedhome = $_REQUEST['stayedhome'];
    }
}


// Is the infection count is within range?
if ( $infections < 1 || $infections > 100)
{
    // Code supplied is out of range - redirect to error page.
//    Header("Location: ".$url);
    echo "<h2>Infections ".$infections." out of range</h2>";
    exit;
}

// Count the deaths - they're negative numbers.
$deaths = 0;


for ($i = 0; $i < $infections; $i++)
{
	$deck[$i] = intval($cards[$i]);

	if ($deck[$i]==0 or abs($deck[$i] > 100))
	{
//    	Header("Location: ".$url);
		echo "<h2>Card ".$deck[$i]." out of range</h2>";
	    exit;
	}

	if ($deck[$i] < 0)
	{
		$deaths++;
	}
}

// Let's do some grammar ;)
$people = ($infections < 2) ? "this 1 person" : "these ".$infections." people";

$collection = "Infection";
$choice = " not ";
$caught = "caught";
$died = "died from the disease.";

if ($stayedhome)
{
    $collection = "Protection";
    $choice = "";
    $caught = "did not catch";
    $died = "would have died.";
}

echo "<h2>This is your ".$collection." Collection.</h2>\n";
echo "<h2>Because you chose ".$choice."to get immunized, ".$people." in your community ".$caught." the shadowpox virus from you.</h2>\n";

if ($deaths > 0)
{
    echo "<h2>The ".$deaths." with a darker background ".$died."</h2>";
}

echo "<h3>Scroll down to the bottom of the page to add your own Shadowpox character.</h3>\n";
echo "</div>\n";
echo "</div>\n";

// Load the nanostories
$nanostories = array_map('str_getcsv', file("nanostories.csv"));
$count = count($nanostories);

// Deal the cards.
foreach ($deck as &$pick)
{
    // Extract our content with meaningful names.
    list($name, $img, $story) = $nanostories[abs($pick)];

    // We need to add an extra class if the current card is dead.
    $class = ($pick < 0) ? " dead" : "";

    echo "<div id='".$name."' class='roundrect".$class."'>\n";

    // Output image, name and nanostory.
//    echo $bIsKiosk ? "<a href='".$url."'/>" : "";
    echo "<img src='/png/".$img."' id='img".sprintf("%02d", abs($pick))."'/><br/>\n";
//    echo $bIsKiosk ? "</a>" : "";

    echo "<h2>".$name."</h2>\n";
    echo "<i>".$story."</i></br>\n";
    
    echo "</div>\n\n";
}

echo '<br/><br/><iframe class="roundrect" src="https://docs.google.com/forms/d/e/1FAIpQLSd7-jRJjHG2e22YP1crQKw6nXTcnfefL63Gyi2t3oJzjQPlxg/viewform?embedded=true" width="400" height="1200" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe></div></body></html>';

exit;
?>

