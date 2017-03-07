<?php
// Report all PHP errors
error_reporting(-1);

// By default we've no code, and we don't know the vaccination status.
$code = 0;
$vaccinated = false;

// The first set will be numbered with three digits, 001 to 099, for people who chose the vaccine option
// The second set's URL codes use two digits, 01 to 99, for the people who chose not to be vaccinated.

// We must be explicity passed a code as a parameter (we're no longer relying on being embedded in a Blogger URL).
if ( isset($_REQUEST['code']) && !empty($_REQUEST['code']))
{
	// We force to an integer to strip any prefixed zero(s).
	$code = intval($_REQUEST['code']);
}

// ... and check it's within range.
if ( $code < 1 || $code > 100)
{
        // Code supplied is out of range - redirect to error page.
         Header("Location: BadCodeError.html");
         exit;
}

// Have we been explicity passed vaccination status?
if ( isset($_REQUEST['vaccinated']) && !empty($_REQUEST['vaccinated']))
{
	$vaccinated = $_REQUEST['vaccinated'];
}

// Let's do some grammar ;)
$people = ($code < 2) ? "this [ 1 ] person" : "these [ ".$code." ] people";

// The heading! TODO: Put it in a div.
if ($vaccinated)
{
	echo "<h2>This is your Protection Collection.</h2>\n";

	echo "<h2>Because you chose to get immunized, ".$people." in your community did not catch the shadowpox virus from you.</h2>\n";
}
else
{
	echo "<h2>This is your Infection Collection.</h2>\n";
	echo "<h2>Because you chose not to get immunized, ".$people." in your community caught the shadowpox virus from you.</h2>\n";
}

// One hundred names (plus dummy name zero).
$names = array("","Abi","Agu","Ali","Ami","Ari","Ati","Bao","Bel","Bob","Cai","Cat","Chu","Dai","Daw","Dee","Des","Dua","Ebi","Efe","Eir","Eka","Eli","Eus","Fai","Fil","Gad","Gen","Gry","Hai","Hie","Ian","Ife","Ima","Ion","Ira","Iva","Jai","Jas","Jen","Jil","Jon","Jud","Kai","Kei","Keo","Kim","Kol","Lee","Liu","Liz","Lon","Luz","Mai","Meg","Mia","Moe","Nai","Nev","Nic","Nox","Oba","Oko","Oma","Ora","Osk","Pat","Peg","Qiu","Raj","Rao","Rik","Rui","Sam","Sei","Sia","Sol","Sue","Tad","Taj","Ted","Teo","Tim","Tor","Udo","Ulf","Una","Usi","Var","Viv","Vuk","Wid","Xan","Xue","Yaz","Yue","Yun","Zaw","Zhi","Zil","Zoe");

// Create a zero-index array containing files in png that aren't . or ..
$pngnames = array_values(array_diff(scandir("png"), array(".", "..")));

echo '<!-- '; print_r($pngnames); echo ' --->';
$pngcount = count($pngnames);

// Create a shuffled deck of cards numbered 1-99
$deck = range(1,99);
shuffle($deck);

// Take the first N cards, then sort in order.
$deck = array_slice($deck, 0, $code);
sort($deck);

echo '<!-- '; print_r($deck); echo ' --->';


// Deal the cards.
foreach ($deck as &$pick)
{
	// The png number is the pick, modulo the number of images.
	$png = ($pick-1) % $pngcount;
	echo "<!-- ".$png." --->\n";

	echo "<img src='/png/".$pngnames[$png]."' /><br/>";
	echo "<h2>".$names[$pick]."</h2>";
	echo "<i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sapien lacus, dictum quis erat sed, vestibulum accumsan turpis. In vehicula dignissim est. Nunc et dui ullamcorper, ullamcorper ipsum a, consequat</i></br>";
	echo "<hr/>";
}

?>
