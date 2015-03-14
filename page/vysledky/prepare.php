<?php
require_once PHP_PATH.'spockmath.php';

function redirect404() {
	header('HTTP/1.0 404 Not Found');
	header('Location: '.WEB_ROOT.'404');
	exit;
}

if (!loggedIn())
	redirect404();

function renderSadaTable($jmeno) {
	$sets = getAnsweredSets($jmeno);
	$out = '<table><tr><td>Čas</td><td>Jméno</td></tr>';
	foreach($sets as $set) {
		$out .= '<tr>';
		$out .= '<td>'.$set->datum.'</td>';
		$out .= '<td><a href="'.WEB_ROOT.'vysledky/'.$set->id.'">'.$set->jmeno.'</a></td>';
		$out .= '</tr>';
	}
	$out .= '</table>';
	return $out;
}
