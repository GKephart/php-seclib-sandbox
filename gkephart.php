<?php
require_once("Secret.php");
$secret = (object) [
	"I" => "Love",
	"Jean" => "Luc"
];

$config = [];
$config["hostname"] = "localhost";
$config["username"] = "gkephart-dba";
$config["password"] = "thought-you-were-going-to get-my-password?";
$config["database"] = "gkephart";
$config["secret"] = json_encode($secret);

writeConfig($config, "/home/gkephart/sec-lib/gkephart.ini");
