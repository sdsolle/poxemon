<?php
// Report all PHP errors
error_reporting(-1);

// Test whether we've been given a code
if ( !isset($_REQUEST['code']) || empty($_REQUEST['code']))
{
	// No code supplied - redirect to error page.
         Header("Location: NoCodeError.html");
         exit;
}

// Get our code fromn the URL param supplied, and check it's within range.
$code = $_REQUEST['code'];

if ( $code < 0 || $code > 100)
{
        // Code supplied is out of range - redirect to error page.
         Header("Location: BadCodeError.html");
         exit;
}

// One hundred names.
$names = array("zero","one","two","three","four","five","six","seven","eight","nine","ten","eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen","eighteen","nineteen","twenty","twenty-one","twenty-two","twenty-three","twenty-four","twenty-five","twenty-six","twenty-seven","twenty-eight","twenty-nine","thirty","thirty-one","thirty-two","thirty-three","thirty-four","thirty-five","thirty-six","thirty-seven","thirty-eight","thirty-nine","forty","forty-one","forty-two","forty-three","forty-four","forty-five","forty-six","forty-seven","forty-eight","forty-nine","fifty","fifty-one","fifty-two","fifty-three","fifty-four","fifty-five","fifty-six","fifty-seven","fifty-eight","fifty-nine","sixty","sixty-one","sixty-two","sixty-three","sixty-four","sixty-five","sixty-six","sixty-seven","sixty-eight","sixty-nine","seventy","seventy-one","seventy-two","seventy-three","seventy-four","seventy-five","seventy-six","seventy-seven","seventy-eight","seventy-nine","eighty","eighty-one","eighty-two","eighty-three","eighty-four","eighty-five","eighty-six","eighty-seven","eighty-eight","eighty-nine","ninety","ninety-one","ninety-two","ninety-three","ninety-four","ninety-five","ninety-six","ninety-seven","ninety-eight","ninety-nine","one hundred");

// Pick 'n' random cards.

for ($i = 1; $i <= $code; $i++)
{

	$pick = rand(1,100);

	echo "<img src='PNGs/".$pick.".png' /><br/>";
	echo "<h2>".$names[$pick]."</h2>";
	echo "<i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sapien lacus, dictum quis erat sed, vestibulum accumsan turpis. In vehicula dignissim est. Nunc et dui ullamcorper, ullamcorper ipsum a, consequat</i></br>";
	echo "<hr/>";
}

?>
