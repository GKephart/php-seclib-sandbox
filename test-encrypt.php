<?php

require_once("Secret.php");

$password   = "--PASSWORD--";
$salt = "salt";


//run the methods that are being tested

try {

	$cipherText = file_get_contents("/home/gkephart/sec-lib/gkephart.ini");

	$decodedTestData = readConfig("/home/gkephart/sec-lib/gkephart.ini");

	var_dump($decodedTestData);


} catch(\InvalidArgumentException $exception) {
	echo $exception->getMessage();

}





