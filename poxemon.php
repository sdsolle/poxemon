<?php
// Report all PHP errors
error_reporting(-1);

// By default, we've no code.
$code = 0;

// Use the refering URL to obtain the code.
if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"]))
{
	// Get our code from the URL doing the embedding ...
	$code = basename($_SERVER["HTTP_REFERER"],".html");
}

// If we've been explicity passed a code as a paramater, overrride the one from the URL.
if ( isset($_REQUEST['code']) && !empty($_REQUEST['code']))
{
	$code = $_REQUEST['code'];
}

// ... and check it's within range.
if ( $code < 1 || $code > 100)
{
        // Code supplied is out of range - redirect to error page.
         Header("Location: BadCodeError.html");
         exit;
}

// One hundred names (plus dummy name zero).
$names = array("","Abi","Agu","Ali","Ami","Ari","Ati","Bao","Bel","Bob","Cai","Cat","Chu","Dai","Daw","Dee","Des","Dua","Ebi","Efe","Eir","Eka","Eli","Eus","Fai","Fil","Gad","Gen","Gry","Hai","Hie","Ian","Ife","Ima","Ion","Ira","Iva","Jai","Jas","Jen","Jil","Jon","Jud","Kai","Kei","Keo","Kim","Kol","Lee","Liu","Liz","Lon","Luz","Mai","Meg","Mia","Moe","Nai","Nev","Nic","Nox","Oba","Oko","Oma","Ora","Osk","Pat","Peg","Qiu","Raj","Rao","Rik","Rui","Sam","Sei","Sia","Sol","Sue","Tad","Taj","Ted","Teo","Tim","Tor","Udo","Ulf","Una","Usi","Var","Viv","Vuk","Wid","Xan","Xue","Yaz","Yue","Yun","Zaw","Zhi","Zil","Zoe");

// Create a zero-index array containing files in png that aren't . or ..
$pngnames = array_values(array_diff(scandir("../png"), array(".", "..")));

//echo '<pre>'; print_r($pngnames); echo '</pre>';
$pngcount = count($pngnames);

// Create a shuffled deck of cards numbered 1-100
$deck = range(1,100);
shuffle($deck);

// Take the first N cards, then sort in order.
$deck = array_slice($deck, 0, $code);
sort($deck);


// Deal the cards.
foreach ($deck as &$pick)
{
	// The png number is the pick modulo the number of images.
	$png = ($pick-1) % $pngcount;

	echo "<img src='/png/".$pngnames[$png]."' /><br/>";
	echo "<h2>".$names[$pick]."</h2>";
	echo "<i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sapien lacus, dictum quis erat sed, vestibulum accumsan turpis. In vehicula dignissim est. Nunc et dui ullamcorper, ullamcorper ipsum a, consequat</i></br>";
	echo "<hr/>";
}

?>
