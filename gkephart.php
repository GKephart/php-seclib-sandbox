<?php
require_once("encrypted-config.php");
$config = [];
$config["hostname"] = "localhost";
$config["username"] = "gkephart-dba";
$config["password"] = "thought-you-were-going-to get-my-password?";
$config["database"] = "gkephart";

writeConfig($config, "/home/gkephart/sec-lib/gkephart.ini");
