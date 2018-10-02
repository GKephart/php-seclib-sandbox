<?php
require_once("Secret.php");
$secret = (object) [
	"I" => "Love",
	"Jean" => "Luc"
];

$secrets = [];
$secrets["hostname"] = "localhost";
$secrets["username"] = "gkephart-dba";
$secrets["password"] = "thought-you-were-going-to get-my-password?";
$secrets["database"] = "gkephart";
$secrets["secret"] = json_encode($secret);

$hideSecrets = new Secret();
$hideSecrets->setSecrets($secrets, "/home/gkephart/sec-lib/gkephart.ini");
