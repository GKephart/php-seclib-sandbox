<?php

require_once("aes256.php");

$originalPlaintext = "ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz";
$cipherText = aes256Encrypt($originalPlaintext, "password");
echo "binary WAT: " . $cipherText . PHP_EOL;
$hexCipherText = bin2hex($cipherText) . PHP_EOL;
echo"hex cipherText: " . $hexCipherText . PHP_EOL;
echo "bullshit decryption: " . $bullshit =aes256Decrypt($cipherText, "password"). PHP_EOL;





