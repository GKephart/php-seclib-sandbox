<?php

require_once("aes256.php");

for($i = 1; $i <= 5; $i++) {

	$plaintext = bin2hex(random_bytes(100));

	echo "plain text" . PHP_EOL;
	echo $plaintext . PHP_EOL;
	echo PHP_EOL;
	echo "cipher text" . PHP_EOL;
	$cipherText = aes256Encrypt($plaintext, "password");
	echo $cipherText . PHP_EOL;
	echo PHP_EOL;
	echo "occurrences of + " . PHP_EOL;
$explodedArray =	explode($cipherText, "+");

foreach($explodedArray as $element) {
	echo strlen($element);

}
	echo PHP_EOL;
}

