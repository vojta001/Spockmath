<?php

require PHP_PATH.'spockmath.php';
$cssStyles[] = 'editor.css';

function renderQtab() {
	global $mysqli, $QT_STR;
	
	$eo = array('even', 'odd');
	$row = 0;
//	$eo = array('odd', 'even');
	
	$qRows = $mysqli->query('SELECT id, typ, data2, data, comment, multi FROM `otazka` ORDER BY `id`');
	if (!$qRows) {
		flm("Dotaz na vše z `otazka` selhal.", '', MSG_ERROR);
		return array();
	}
	$out = '<table><tr><th>Číslo</th><th>Typ</th><th>Otázka</th><th>Komentář</th><th>Multi</th></tr>';
	
	while($q = $qRows->fetch_object()) {
		$otazka = ($q->typ == 1) ? $q->data : $q->data2;
		$out .= '<tr class="'.$eo[($row++)%2].'"><td>'.$q->id.'</td><td>'.$QT_STR[$q->typ].'</td><td>'.$otazka.'</td><td>'.$q->comment.'</td><td>'.($q->multi?'Ano':'Ne').'</td></tr>';
	}
	
	$out .= '<tr class="'.$eo[($row++)%2].'"><td colspan="5">Přidat novou</td></tr>';
	$out .= '</table>';
	return $out;
}



