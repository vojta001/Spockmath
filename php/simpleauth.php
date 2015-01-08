<?php




function isValidLogin($user, $passwd) {
	return $user == 'houba';
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
