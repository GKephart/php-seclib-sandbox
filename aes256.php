<?php
require_once(__DIR__ . "/vendor/autoload.php");
use phpseclib\Crypt\AES;
use phpseclib\Crypt\Random;
/**
 * encrypts text using AES 256 CBC mode using openssl_encrypt()
 *
 * @param string $plaintext plaintext to encrypt
 * @param string $password plaintext symmetric key
 * @return string base 64 encoded ciphertext
 * @throws
 * @see http://php.net/manual/en/function.openssl-encrypt.php
 **/
function aes256Encrypt($plaintext, $password) {

	//initialize the AES class for php-sec-lib2
	$cipher = new AES();

	//set the password (according to the documentation this line is equivalent to $cipher->setPassword('whatever', 'pbkdf2', 'sha1', 'phpseclib/salt', 1000, 256 / 8);
	$salt = bin2hex(random_bytes(36 ));
	$cipher->setPassword($password,  "sha3-256", $salt);
	$iv = bin2hex(random_bytes(32));
	$cipher->setIV($iv);


;

	$rawCipherText = $cipher->encrypt($plaintext);
	$cipherText = new stdClass();
	$cipherText->raw=$rawCipherText;
	$cipherText->iv = $iv;
	$cipherText->salt = $salt;



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
 * @return string decrypted plaintext
 * @throws InvalidArgumentException if the plaintext can't be unpadded
 * @see http://php.net/manual/en/function.openssl-decrypt.php
 **/
function aes256Decrypt( object $ciphertext, $password) {

	//initialize the AES class
	$cipher = new AES();

	//set the password
	$cipher->setPassword($password);
	$iv = Random::string($cipher->getBlockLength() >> 3);
	var_dump($iv);
	$cipher->setIV($ciphertext->iv);

	//decrypt the cipher text
	$plaintext = $cipher->decrypt($ciphertext->raw);

	if ($plaintext === false) {
		throw new InvalidArgumentException("cipher text could not be encrypted");
	}
	return($plaintext);
}