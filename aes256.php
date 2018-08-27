<?php
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
	$iv = random_bytes(openssl_cipher_iv_length("aes-256-cbc"));
	$ciphertext = openssl_encrypt($plaintext, "aes-256-cbc", $password, OPENSSL_RAW_DATA, $iv);
	$ciphertext = base64_encode($iv . $ciphertext);
	if ($ciphertext === false) {
		throw new InvalidArgumentException("plaintext could not be encrypted");
	}
	return($ciphertext);
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
function aes256Decrypt($ciphertext, $password) {
	$ivSize = openssl_cipher_iv_length("aes-256-cbc");
	$rawBlock = base64_decode($ciphertext);
	$iv = substr($rawBlock, 0, $ivSize);
	$ciphertext = substr($rawBlock, $ivSize);
	$plaintext = openssl_decrypt($ciphertext, "aes-256-cbc", $password, OPENSSL_RAW_DATA, $iv);
	if ($plaintext === false) {
		throw new InvalidArgumentException("plaintext could not be unpadded");
	}
	return($plaintext);
}