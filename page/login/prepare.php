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
		echo "<br />\nJe potřeba použít <a href=https://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]>https://</a><br />\n Debug info: Headers sent in $file at line $line";
	}
	exit;
}

$cssStyles[] = 'login.css';

function renderUsers() {
	$out = '<div id="users"><p>Admin sekce</p><span>Seznam uživatelů a hashů jejich hesel</span>';
	$out .= '<table>';
	$out .= '<tr class="title"><td>Username</td><td>Password hash</td></tr>';
	foreach (getAllUsers() as $user) {
		$out .= '<tr><td>'.$user->usr.'</td><td>'.$user->passwd.'</td></tr>';
	}
	$out .= '</table></div>';
	return $out;
}

function renderTools(){
	return '<form method="post">'.PHP_EOL.'<input type="password" placeholder="Text (heslo...)" name="tohash" />'.PHP_EOL.'<input type="submit" name="make_hash" value="Zahashuj">'.PHP_EOL.'</form>';
}
