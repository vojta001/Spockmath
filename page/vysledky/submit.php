<?php
flm ($_POST, 'POST');
require_once 'prepare.php';

if (!empty($_POST['jmeno'])) {
	$_SESSION['vysledky']['user'] = $_POST['jmeno'];
}
