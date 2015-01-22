<?php





function isValidLogin($user, $passwd) {;
	return password_verify ($passwd, getHashDB($user));
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
	return crypt ($user);
}

function isAdmin($user) {
	global $admins;
	return in_array($user, $admins);
}
