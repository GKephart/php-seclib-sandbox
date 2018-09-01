<?php
require_once(__DIR__ . "/vendor/autoload.php");
use phpseclib\Crypt\AES;
use phpseclib\Crypt\Random;
/**
 * encrypts text using AES 256 CBC mode using openssl_encrypt()
 *
 * @param string $plaintext plaintext to encrypt
 * @param string $password plaintext symmetric key
 * @param string $salt salt used for encryption and decryption
 * @return string base 64 encoded ciphertext
 * @throws
 * @see http://php.net/manual/en/function.openssl-encrypt.php
 **/
function aes256Encrypt( string $plaintext, string $password , string $salt) : string {

	//initialize the AES class for php-sec-lib2
	$cipher = new AES();

	//set the password (according to the documentation this line is equivalent to $cipher->setPassword('whatever', 'pbkdf2', 'sha1', 'phpseclib/salt', 1000, 256 / 8);



	$cipher->setPassword($password, "pbkdf2", "sha3-256", $salt);
	$iv = bin2hex(random_bytes(32));
	$cipher->setIV($iv);
	$cipherText = $cipher->encrypt($plaintext);

	$cipherText = $cipherText . "." . $iv;

	if ($cipherText === false) {
		throw new InvalidArgumentException("plaintext could not be encrypted");
	}

	return($cipherText);
}

/**
 * decrypts text using AES 256 CBC mode using openssl_decrypt()
 *
 * @param string $ciphertext base 64 encoded ciphertext
 * @param string $password plaintext symmetric key
 * @param string $salt salt used for encryption and decryption
 * @return string decrypted plaintext
 * @throws InvalidArgumentException if the pla
 * @see http://php.net/manual/en/function.openssl-decrypt.php
 **/
function aes256Decrypt( string $ciphertext, string $password,string $salt) : string {

	//initialize the AES class
	$cipher = new AES();

	//set the password
	$cipher->setPassword($password, "pbkdf2","sha1", $salt);

	$cipher->setIV();

	//decrypt the cipher text
	$plaintext = $cipher->decrypt($ciphertext);

	if ($plaintext === false) {
		throw new InvalidArgumentException("cipher text could not be encrypted");
	}

	return($plaintext);
}