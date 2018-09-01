<?php

require_once("aes256.php");

//setup for the test data and the salt
$testData = (object)["foo" => "bar", "baz" => "qux", "quux" => "quuz"];
$jsonfiedTestData = json_decode($testData);
$salt = bin2hex(random_bytes(32));


//run the methods that are being tested
for($i = 0; $i <= 100; $i++) {
	try {
		$cipherText = aes256Encrypt($jsonfiedTestData, "password", $salt);
		aes256Decrypt($cipherText, "password", $salt);
	} catch(\InvalidArgumentException $exception) {
		echo "you done messed up Aaron";
	}
}





