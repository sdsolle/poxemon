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
a
{
	color: white;
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
.hospitalised
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
$victims = 0;
$stayedhome = false;
$demoscreen = false;
$highscore = "These are all the people you can save by staying inside.";

// We must be explicity passed a card list as a parameter
if ( isset($_REQUEST['cards']) && !empty($_REQUEST['cards']))
{
	// Build the deck from the cards param passed in.
	// Any elements beyond 99 are left in the 100th entry.
	$cards = explode(',', $_REQUEST['cards'], 100);
	$infections = count($cards);

    // Have we been explicity passed stayed-at-home status?
    if ( isset($_REQUEST['stayedhome']) && !empty($_REQUEST['stayedhome']))
    {
        $stayedhome = $_REQUEST['stayedhome'];
    }

}

// If we're explicitly asked for a demo, or received a GET request, then show the demo screen.
if ( isset($_REQUEST['demoscreen']) || $_SERVER['REQUEST_METHOD'] != 'POST' )
{
	$stayedhome = true;
    $demoscreen = true;
	$infections = 99;
}


// Is the infection count is within range?
if ( $infections < 0 || $infections > 99)
{
    // Code supplied is out of range - rather than redirect to error page, we show all cards.
	$demoscreen = true;
	$infections = 99;
}

// Negative numbers represent hospitalisations.
$victims = 0;
$decksize = $infections;

// Zero infections means show all cards.
if ($infections == 0)
{
	$highscore = "Congratulations! Because you stayed at home, no one in your community caught the shadowpox virus from you.";
	$stayedhome = true;
	$decksize = 99;
}

for ($i = 0; $i < $decksize; $i++)
{
	if ($demoscreen || $stayedhome)
	{
		// Deal a dummy deck.
		$deck[$i] = $i + 1;
	}
	else
	{
		// Use the deck supplied.
		$deck[$i] = intval($cards[$i]);

		// Force cards to range
		if ($deck[$i]==0 || abs($deck[$i]) > 100)
		{
 			$deck[$i] = ($deck[$i] % 100)+1;
		}

		if ($deck[$i] < 0)
		{
			$victims++;
		}
	}
}

// Let's do some grammar ;)
$people = ($infections < 2) ? "this 1 person" : "these ".$infections." people";

if ($stayedhome)
{
	echo "<h2>This is your Protection Collection.</h2>\n";
	echo "<h2>".$highscore."</h2>\n";
}
else
{
	echo "<h2>This is your Infection Collection.</h2>\n";

	echo "<h2>Because you chose not to stay home, ".$people." in your community caught the shadowpox virus from you.</h2>\n";

	if ($victims > 0)
	{
    	echo "<h2>The ".$victims." with a darker background needed hospital care.</h2>";
	}
}


//echo "<h3>Scroll down to the bottom of the page to add your own Shadowpox character.</h3>\n";
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

    // We need to add an extra class if the current card is hospitalised.
    $class = ($pick < 0) ? " hospitalised" : "";

    echo "<div id='".$name."' class='roundrect".$class."'>\n";

    // Output image, name and nanostory.
    echo "<img src='/png/".$img."' id='img".sprintf("%02d", abs($pick))."'/><br/>\n";

    echo "<h2>".$name."</h2>\n";
    echo "<i>".$story."</i></br>\n";

    echo "</div>\n\n";
}

echo '<br/><br/>';
echo '<h2><a href="/game">Try Again</a></h2>';
echo '<h2><a href="https://blog.shadowpox.org/p/game-credits.html">Game Credits and More Info</a></h2>';
echo '</br></div></body></html>';

exit;
?>
