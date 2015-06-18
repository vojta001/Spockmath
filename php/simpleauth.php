<?php

define('PERM_ADMIN', 1);
define('PERM_UCITEL', 3);

function isValidLogin($user, $passwd) {
	return password_verify($passwd, getHashDB($user));
}

function loggedIn() {
	return isset($_SESSION['user']);
}

function loggOut() {
	unset($_SESSION['user']);
}

function loggIn($username) {
	$_SESSION['user'] = $username;
}

function getUser() {
	if (loggedIn())
		return $_SESSION['user'];
	else
		return false;
}

function getAllUsers() {
	return getDBUsers();
}

function makeHash($user) {
	return crypt($user);
}

function getPerm($user) {
	if ($perm = getDBUser($user)->perm)
		return $perm;
	else
		return false;
}

function renderLoginLogoutLink() {
	if (loggedIn())
		return '<a href="https://'.$_SERVER['SERVER_NAME'].WEB_ROOT.'login/out"><img src="'.IMG_PATH.'design/logout.png" alt="login icon" title="Ohlásit" /></a>';
	else
		return '<a href="https://'.$_SERVER['SERVER_NAME'].WEB_ROOT.'login"><img src="'.IMG_PATH.'design/login.png" alt="login icon" title="Příhlásit" /></a>';
}
