<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=576" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet" />
<style>
body
{
    font-family: 'Jura', sans-serif;
    text-align: center;
    width: 576px;
    margin: auto;
}
#header
{
    position:relative;
    height: 400px;
}
#intro
{
    width: 510px;
    padding-left: 28px;
    padding-right: 28px;
    position:absolute;
    bottom:0;
}
#roundrect
{
    width: 420px;
    border-radius: 25px;
    border: 2px solid;
    padding: 20px;
    margin-top: 20px;
    margin-left: auto;
    margin-right: auto;
}
</style>
</head>
<body>
<div id='container'>
<div id='header'>
<div id='anim'>
<img src='/poxemon.gif' />
</div>
<div id='intro'>

<?php
// Report all PHP errors
error_reporting(-1);

// By default we've no infections, and we don't know the vaccination status.
$infections = 0;
$vaccinated = false;

// The first set will be numbered with three digits, 001 to 099, for people who chose the vaccine option
// The second set's URL codes use two digits, 01 to 99, for the people who chose not to be vaccinated.

// We must be explicity passed a code as a parameter (we're no longer relying on being embedded in a Blogger URL).
if ( isset($_REQUEST['code']) && !empty($_REQUEST['code']))
{
    // We force to an integer to strip any prefixed zero(s).
    $infections = intval($_REQUEST['code']);

    // Have we been explicity passed vaccination status?
    // (Vaccinated paramater only makes sense if we've also been passed a code)
    if ( isset($_REQUEST['vaccinated']) && !empty($_REQUEST['vaccinated']))
    {
        $vaccinated = $_REQUEST['vaccinated'];
    }
}

// ... and check it's within range.
if ( $infections < 1 || $infections > 100)
{
    // Code supplied is out of range - redirect to error page.
    Header("Location: BadCodeError.html");
    exit;
}

// Let's do some grammar ;)
$people = ($infections < 2) ? "this 1 person" : "these ".$infections." people";

$collection = "Infection";
$choice = " not ";
$caught = "caught";

if ($vaccinated)
{
    $collection = "Protection";
    $choice = "";
    $caught = "did not catch";
}

echo "<h2>This is your ".$collection." Collection.</h2>\n";
echo "<h2>Because you chose ".$choice."to get immunized, ".$people." in your community ".$caught." the shadowpox virus from you.</h2>\n";
echo "<h3>Scroll down to the bottom of the page to add your own Shadowpox character.</h3>\n";
echo "</div>\n";
echo "</div>\n";

// Load the nanostories
$nanostories = array_map('str_getcsv', file("nanostories.csv"));
$count = count($nanostories);

// Create a shuffled deck, with one card for each nanostory.
$deck = range(0, $count-1);
shuffle($deck);

// Take the first N cards, then sort in order.
$deck = array_slice($deck, 0, $infections);
sort($deck);

// Deal the cards.
foreach ($deck as &$pick)
{
    $card = $nanostories[$pick];

    echo "<!-- ".$pick." --->\n";

    echo "<div id='roundrect'>\n";
    echo "<img src='/png/".$card[1]."' /><br/>\n";
    echo "<h2>".$card[0]."</h2>\n";
    echo "<i>".$card[2]."</i></br>\n";
    echo "</div>\n\n";
}

?>
<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSd7-jRJjHG2e22YP1crQKw6nXTcnfefL63Gyi2t3oJzjQPlxg/viewform?embedded=true" width="400" height="1200" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
</div>
</body>
</html>
