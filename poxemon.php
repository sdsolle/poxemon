<?php
// Report all PHP errors
error_reporting(-1);

// Test whether we've been given a code
if ( !isset($_SERVER["HTTP_REFERER"]) || empty($_SERVER["HTTP_REFERER"]))
{
	// Unable to determine referer - redirect to error page.
         Header("Location: NoRefError.html");
         exit;
}

// Get our code from the URL doing the embedding ...
$code = basename($_SERVER["HTTP_REFERER"],".html");

// ... and check it's within range.
if ( $code < 1 || $code > 100)
{
        // Code supplied is out of range - redirect to error page.
         Header("Location: BadCodeError.html");
         exit;
}

// One hundred names (plus dummy name zero).
$names = array("","Abi","Agu","Ali","Ami","Ari","Ati","Bao","Bel","Bob","Cai","Cat","Chu","Dai","Daw","Dee","Des","Dua","Ebi","Efe","Eir","Eka","Eli","Eus","Fai","Fil","Gad","Gen","Gry","Hai","Hie","Ian","Ife","Ima","Ion","Ira","Iva","Jai","Jas","Jen","Jil","Jon","Jud","Kai","Kei","Keo","Kim","Kol","Lee","Liu","Liz","Lon","Luz","Mai","Meg","Mia","Moe","Nai","Nev","Nic","Nox","Oba","Oko","Oma","Ora","Osk","Pat","Peg","Qiu","Raj","Rao","Rik","Rui","Sam","Sei","Sia","Sol","Sue","Tad","Taj","Ted","Teo","Tim","Tor","Udo","Ulf","Una","Usi","Var","Viv","Vuk","Wid","Xan","Xue","Yaz","Yue","Yun","Zaw","Zhi","Zil","Zoe");

// A list of filenames.
$pngnames = array("noun_597393","vaccination-sign","wild-virus-sign","pox-noun_25857","biohazard_noun_78740","contact-tracing_noun_203956","edX-shadowpox-logo","isolation","light-shadow_noun_10635","noun_64228","noun_87978","noun_98901","noun_98903","noun_165966","noun_170457","noun_221949","pox-face_noun_38487","pox-noun_25857","shadow-double_noun_42858","Shadowpox-logo-1","Shadowpox-logo-2-shape","Shadowpox-logo-2","syringe_noun_7402","toropox-moon_black_noun_2700","toropox-moon_noun_2700","volunteer2");

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

	echo "<img width='100' height='100' src='PNGs/".$pngnames[$png].".png' /><br/>";
	echo "<h2>".$names[$pick]."</h2>";
	echo "<i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sapien lacus, dictum quis erat sed, vestibulum accumsan turpis. In vehicula dignissim est. Nunc et dui ullamcorper, ullamcorper ipsum a, consequat</i></br>";
	echo "<hr/>";
}

?>
