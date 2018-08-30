<?php

require_once("aes256.php");

$originalPlaintext = "ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz";
$cipherText = aes256Encrypt($originalPlaintext, "password");
echo "binary WAT: " . $cipherText;
$hexCipherText = bin2hex($cipherText);
echo"hex cipherText: " . $hexCipherText . PHP_EOL;
echo "bullshit decryption: " . $bullshit =aes256Decrypt($cipherText, "password");





