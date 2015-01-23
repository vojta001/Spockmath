<?php

session_start();

$jsScripts = array();
$cssStyles = array();


require 'simpleauth.php';
require 'database.php';
require 'flashmsg.php';

$page = 'home';
if (isset($_GET['page'])) {
	if (file_exists(PAGES_PATH.$_GET['page']))
		$page = $_GET['page']; //stačí, aby existovala složka
	else
		$page = '404';
}

if (!empty($_POST)) {
	$submitFile = PAGES_PATH.$page.'/submit.php';
	if (file_exists($submitFile))
		include $submitFile;
	
	if (!headers_sent($file, $line)) {
		header("HTTP/1.1 303 See Other");
		header("Location: //$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
	} else {
		echo "\nHeaders sent in $file at line $line";
	}
	
	exit;
}

$jsFileName = JS_PATH.$page.'.js';
if (file_exists($jsFileName))
  $jsScripts[] = $jsFileName;

$cssFileName = CSS_PATH.$page.'.css';
if (file_exists($cssFileName))
  $cssStyles[] = $cssFileName;

$cssStyles[] = "style.css";


require 'bubble.php';
require 'menu.php';


$prepFile = PAGES_PATH.$page.'/prepare.php';
if (file_exists($prepFile))
include $prepFile;

flm($_GET, '$_GET is:');
//flm($_POST, '$_POST is:');
flm($page, '$page is:');

  