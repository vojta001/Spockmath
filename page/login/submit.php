<?php

require_once 'prepare.php';

if (isValidLogin($_POST['usrname'], $_POST['passwd'])) {
	loggIn($_POST['usrname']);
	flm('Vítejte '.$_POST['usrname']);
}
