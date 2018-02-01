<?php

### NGINX routing hack
$uriParts = explode("/", $_SERVER["REQUEST_URI"]);
if (!empty($uriParts[1])) {
	$_GET["page"] = $uriParts[1];
}

if (!empty($uriParts[2])) {
	$_GET["q"] = $uriParts[2];
}

require_once 'config.php';
require_once PHP_PATH.'boot.php';
require_once PHP_PATH.'render.php';
