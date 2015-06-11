<?php

require_once 'prepare.php';

if (loggedIn()) {
	if (isset($_POST['make_hash']) && !empty($_POST['tohash']) && isAdmin(getUser()))
		flm(makeHash($_POST['tohash']), 'Hash vstupu jest:', MSG_INFO);
} elseif (!empty($_POST['usrname'])) {
	if (isValidLogin($_POST['usrname'], $_POST['passwd'])) {
		loggIn($_POST['usrname']);
		flm('Vítejte '.$_POST['usrname'], '', MSG_INFO);
	} else{
		flm('Neplatné přihlašovací údaje', 'Přihlášení', MSG_WARNING);
	}
}
