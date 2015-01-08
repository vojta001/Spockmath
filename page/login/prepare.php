<?php

if (isset($_GET['q']) && $_GET['q'] == 'out') {
	loggOut();
	  header("HTTP/1.1 303 See Other");
	  header('Location: https://'.$_SERVER['SERVER_NAME'].WEB_ROOT.$page);
		exit;
}

if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
  if (!headers_sent($file, $line)) {
	  //header("HTTP/1.1 303 See Other");
	  header("Location: https://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
	} else {
		echo "<br />\nJe potýeba pou§¡t <a href=https://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]>https://</a><br />\n Debug info: Headers sent in $file at line $line";
	}
	exit;
}
$cssStyles[] = 'login.css';


