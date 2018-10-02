<?php

require_once("Secret.php");

$expectedSecret = (object) [
	"I" => "Love",
	"Jean" => "Luc"
];

try {

	$secrets = new Secret();
	$actualSecret = $secrets->getSecret("/home/gkephart/sec-lib/gkephart.ini", "secret");

	if ($expectedSecret == $actualSecret) {
		echo "I am a little tea pot \n";
	}

	$badSecret = $secrets->getSecret("/home/gkephart/sec-lib/gkephart.ini", "username");
} catch(\InvalidArgumentException $exception) {
	echo $exception->getLine() . " " . $exception->getFile() . " ". $exception->getMessage(). "\n";
}





