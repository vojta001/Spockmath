<?php

require_once 'prepare.php';

if (isValidLogin($_POST['usrname'], $_POST['passwd'])) {
	loggIn($_POST['usrname']);
	flm('Vítejte '.$_POST['usrname']);
}elseif (isset($_POST['make_hash']) && isset($_POST['tohash']) && $_POST['tohash']) {
	if (isAdmin(getUser())) {
		flm (makeHash(), 'Hash vstupu jest:');
	}
}
