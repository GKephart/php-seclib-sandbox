<?php
require_once(__DIR__ . "/vendor/autoload.php");
use phpseclib\Crypt\AES;


class Secret {

	private $password = "--PASSWORD--";

	/**
	 * encrypts text using AES 256 CBC mode using openssl_encrypt()
	 *
	 * @param string $plaintext plaintext to encrypt
	 * @return string hex encoded ciphertext
	 * @throws
	 * @see http://php.net/manual/en/function.openssl-encrypt.php
	 **/
	private function aes256Encrypt(string $plaintext): string {

		//initialize the AES class for php-sec-lib2
		$cipher = new AES();

		$salt = bin2hex(random_bytes(128));

		//set the password (according to the documentation this line is equivalent to $cipher->setPassword('whatever', 'pbkdf2', 'sha1', 'phpseclib/salt', 1000, 256 / 8);
		$cipher->setPassword($this->password, "pbkdf2", "sha3-256", $salt);
		$iv = bin2hex(random_bytes(128));
		$cipher->setIV($iv);
		$cipherText = $cipher->encrypt($plaintext);

		echo strlen($cipherText);


		$cipherText = bin2hex($cipherText);
		echo strlen($cipherText);
		$cipherText = $cipherText . "." . $iv . "." . $salt;

		if($cipherText === false) {
			throw new InvalidArgumentException("plaintext could not be encrypted");
		}

		return ($cipherText);
	}

	/**
	 * decrypts text using AES 256 CBC mode using openssl_decrypt()
	 *
	 * @param string $ciphertext base 64 encoded ciphertext
	 * @param string $password plaintext symmetric key
	 * @param string $iv $iv used for encryption/decryption
	 * @param string $salt salt used for encryption and decryption
	 * @return string decrypted plaintext
	 * @throws InvalidArgumentException if the pla
	 * @see http://php.net/manual/en/function.openssl-decrypt.php
	 **/
	private function aes256Decrypt(string $ciphertext, string $iv, string $salt): string {

		//convert the ciphertext from hex to binary
		$ciphertext = bin2hex($ciphertext);

		//initialize the AES class
		$cipher = new AES();

		//set the password
		$cipher->setPassword($this->password, "pbkdf2", "sha3-256", $salt);

		//grab the iv off of the cipher text.
		$cipher->setIV($iv);

		//decrypt the cipher text
		$plaintext = $cipher->decrypt($ciphertext);

		if($plaintext === false) {
			throw new InvalidArgumentException("cipher text sucks!!", 18);
		}

		return ($plaintext);
	}

	/**
	 * reads an encrypted configuration file and decrypts and parses the parameters
	 *
	 * @param string $filename encrypted file name to read
	 * @return array all the parameters parsed from the configuration file
	 * @throws InvalidArgumentException if parsing or decryption is unsuccessful
	 **/
	private function readConfig($filename) {

		// verify the file is readable
		if(is_readable($filename) === false) {
			throw(new InvalidArgumentException("configuration file is not readable"));
		}

		// read the encrypted config file
		if(($cipherText = file_get_contents($filename)) == false) {
			throw(new InvalidArgumentException("unable to read configuration file"));
		}

		$cipherTextArray = explode(".", $cipherText);

		if((count($cipherTextArray)) !== 3) {
			throw new InvalidArgumentException("cipher text could not be encrypted.");
		}

		$rawCipherText = $cipherTextArray[0];
		$iv = $cipherTextArray[1];
		$salt = $cipherTextArray[2];

		// decrypt the file
		try {
			// password variable redacted for security reasons :D
			// suffice to say the password is derived from known server variables
			$plaintext = self::aes256Decrypt($rawCipherText, $iv,  $salt);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// parse the parameters and return them
		if(($parameters = parse_ini_string($plaintext)) === false) {
			throw(new InvalidArgumentException("unable to parse parameters"));
		}
		return ($parameters);
	}

	/**
	 * encrypts and writes an array of parameters to a configuration file
	 *gke
	 * @param array $parameters configuration parameters to write
	 * @param string $filename filename to write to
	 * @throws InvalidArgumentException if the parameters are invalid or the file cannot be accessed
	 **/
	public function writeConfig($parameters, $filename) {

		// verify the parameters are an array
		if(is_array($parameters) === false) {
			throw(new InvalidArgumentException("parameters are not an array"));
		}

		// verify the file name is writable
		if(is_writable($filename) === false) {
			throw(new InvalidArgumentException("configuration file is not writable"));
		}

		// build the plaintext to encrypt
		$plaintext = "";

		foreach($parameters as $key => $value) {
			// quote strings
			if(is_string($value) === true) {
				$value = str_replace("\"", "\\\"", $value);
				$value = "\"$value\"";
			}

			// transform booleans to "On" and "Off"
			if(is_bool($value)) {
				if($value === true) {
					$value = "On";
				} else {
					$value = "Off";
				}
			}
			$plaintext = $plaintext . "$key = $value\n";
		}
		// delete the final newline
		$plaintext = substr($plaintext, 0, -1);

		// encrypt the text using the filename
		$ciphertext = self::aes256Encrypt($plaintext);

		// open the config file and write the cipher text
		if(file_put_contents($filename, $ciphertext) === false) {
			throw(new InvalidArgumentException("unable to write configuration file"));
		}
	}

	/**
	 * connects to a mySQL database using the encrypted mySQL configuration
	 *
	 * @param string $filename path to the encrypted mySQL configuration file
	 * @return \PDO connection to mySQL
	 **/
	public function getPdoObject ($filename) : \PDO {



		// grab the encrypted mySQL properties file and create the DSN
		$config = $this->readConfig($filename);
		$dsn = "mysql:host=" . $config["hostname"] . ";dbname=" . $config["database"];
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

		// create the PDO interface and return it
		$pdo = new PDO($dsn, $config["username"], $config["password"], $options);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return ($pdo);
	}

	/**
	 * Function that will return an object of protected variables.
	 *
	 * @param string $needle associative array key of the protected config object
	 * @param string $filename path to the encrypted secrets configuration file
	 * @return object $secret object containing the specified secret TLDR API keys
	 **/

	public function getSecret(string $needle, string $filename) : object {

		// unencrypt the configuration array
		$secretArray = self::readConfig($filename);

		// search for the needle in the haystack.
		$secret = $secretArray[$needle] ?? (bool) false;

		$secret = json_decode($secret);

		if(is_object($secret) === false) {
			throw new \InvalidArgumentException("needle was not found");
		}

		return (object) $secret;
	}
}



