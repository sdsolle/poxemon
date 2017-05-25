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
    height: 480px;
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

// But in kiosk mode, we link back to the kiosk page.
$kiosk_url = "http://".$_SERVER['SERVER_NAME']."/kiosk.html";

$bIsKiosk = isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] == $kiosk_url;

if ( $bIsKiosk )
{
    $url = $kiosk_url;
}

// Output the graphic header, and the intro div.
echo "<a href='".$url."'>\n<img src='/poxemon.gif' />\n</a>\n</div>\n";
echo "<div id='intro'>";

// Report all PHP errors
error_reporting(-1);

// By default we've no infections, and we don't know the vaccination status.
$infections = 0;
$deaths = 0;
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

// The third set uses three letters - AAA to ZZZ - and encodes infections, deaths and vaccination status.
if ( isset($_REQUEST['tla']) && !empty($_REQUEST['tla']))
{
    list($infections, $deaths, $vaccinated) = decode($_REQUEST['tla']);
}

// Is the infection count is within range?
if ( $infections < 1 || $infections > 100)
{
    // Code supplied is out of range - redirect to error page.
    Header("Location: ".$url);
    exit;
}

// Let's do some grammar ;)
$people = ($infections < 2) ? "this 1 person" : "these ".$infections." people";

$collection = "Infection";
$choice = " not ";
$caught = "caught";
$died = "died from the disease.";

if ($vaccinated)
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

// Create a shuffled deck, with one card for each nanostory.
$deck = range(0, $count-1);
shuffle($deck);

// Take the first N cards
$deck = array_slice($deck, 0, $infections);

// From this deck, create a smaller deck holding one card per death.
$dead = array_slice($deck, 0, $deaths);

// Remove the dead cards from the deck.
$deck = array_diff($deck, $dead);

// Sort the infections deck in order.
sort($deck);

// Sort the dead cards in order.
sort($dead);

// Add the dead cards back to the bottom of the deck.
$deck = array_merge($deck, $dead);

// Deal the cards.
foreach ($deck as &$pick)
{
    // Extract our content with meaningful names.
    list($name, $img, $story) = $nanostories[$pick];

    // We need to add an extra class if the current card is dead.
    $class = in_array($pick, $dead, true) ? " dead" : "";

    echo "<div id='".$name."' class='roundrect".$class."'>\n";

    // Output image, name and nanostory.
    echo $bIsKiosk ? "<a href='".$url."'/>" : "";
    echo "<img src='/png/".$img."' id='img".sprintf("%02d", $pick)."'/><br/>\n";
    echo $bIsKiosk ? "</a>" : "";

    echo "<h2>".$name."</h2>\n";
    echo "<i>".$story."</i></br>\n";
    
    echo "</div>\n\n";
}

echo '<br/><br/><iframe class="roundrect" src="https://docs.google.com/forms/d/e/1FAIpQLSd7-jRJjHG2e22YP1crQKw6nXTcnfefL63Gyi2t3oJzjQPlxg/viewform?embedded=true" width="400" height="1200" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe></div></body></html>';

exit;

function decode($code)
{
    // Poxemon custom base 22 alphabet vs normal, starts-from-zero base 22 alphabet.
    $src = array('A','B','C','D','F','G','H','J','K','L','M','N','P','Q','R','S','T','V','W','X','Y','Z');
    $dst = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l');

    $len = strlen($code);

    // Force to uppercase.
    $code = strtoupper($code);

    // Check if we've been passed a non-TLA code.
    if ($len<2 || $len>3 || str_replace($src, "", $code))
    {
        // There are non-alphabet characters here
        return array(-1,-1,-1);
    }

    // Normalise alphabet
    $code = str_replace($src, $dst, $code);

    // Convert from base 22 to binary.
    $code = intval($code, 22);

    // ... and check it's within range.
    if ( $code < 0 || $code > 10648)
    {
        return array(-1,-1,-1);
    }

    // By default we don't know the vaccination status.
    $vaccinated = false;

    if ($code >= 5324)
    {
        // Reset to counting-from-zero.
        $code-= 5324;

        // Player is vaccinated.
        $vaccinated = true;
    }

    // The infection number is the row with the nearest triangle number to our code.
    $infections = intval((sqrt(8*$code + 1) - 1)/2);

    // Re-calculate the nearest triangle number from the row.
    $triangle = intval(($infections * ($infections + 1)) / 2);

    // The triangle number is the row we're starting at, and the deaths are the offset into that row.
    $deaths = $code - $triangle;

    return array($infections, $deaths, $vaccinated);
}

?>

