<?php
flm ($_POST, 'POST');
require_once 'prepare.php';

if (isset($_POST['jmeno'])) {
	$_SESSION['vysledky']['user'] = $_POST['jmeno'];
} elseif(isset($_POST['reset_user'])) {
	unset($_SESSION['vysledky']['user']);
}
