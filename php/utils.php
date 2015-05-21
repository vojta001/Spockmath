<?php

function redirect404() {
	header('HTTP/1.0 404 Not Found');
	header('Location: '.WEB_ROOT.'404');
	exit;
}


