<?php


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

function isAdmin($user) {
	global $admins;
	return in_array($user, $admins);
}

function renderLoginLogoutLink() {
	if (loggedIn())
		return '<a href="https://'.$_SERVER['SERVER_NAME'].'/spock/login/out"><img src="'.IMG_PATH.'design/logout.png" alt="login icon" title="Ohlásit" /></a>';
	else
		return '<a href="https://'.$_SERVER['SERVER_NAME'].'/spock/login"><img src="'.IMG_PATH.'design/login.png" alt="login icon" title="Příhlásit" /></a>';
}
