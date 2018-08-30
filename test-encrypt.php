<?php

require_once("aes256.php");

$plaintext = bin2hex(random_bytes(100));

echo "plain text" . PHP_EOL;
echo $plaintext . PHP_EOL;
echo "cipher text" . PHP_EOL;
echo aes256Encrypt($plaintext, "password" );

