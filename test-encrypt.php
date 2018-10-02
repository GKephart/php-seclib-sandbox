<?php

require_once("Secret.php");

$expectedSecret = (object) [
	"I" => "Love",
	"Jean" => "Luc"
];

try {

	$secrets = new Secret();
	$actualSecret = $secrets->getSecret("/home/gkephart/sec-lib/gkephart.ini", "secret");
	var_dump($actualSecret);

	if ($expectedSecret == $actualSecret) {
		echo "\n I am a little tea pot";
	}

	$badSecret = $secrets->getSecret("/home/gkephart/sec-lib/gkephart.ini", "username");
} catch(\InvalidArgumentException $exception) {
	echo $exception->getMessage();

}





