<?php

require PHP_PATH.'spockmath.php';
$cssStyles[] = 'editor.css';

$_id = getIdFromQuery();

if ($_id === FALSE) {
	header('HTTP/1.0 404 Not Found');
  header('Location: '.WEB_ROOT.'404');
	exit;
}	

function getIdFromQuery() {
	if (isset($_GET['q'])) {
		$id = $_GET['q'];
		if ($id == 'new')
			return 0;
		elseif (is_numeric($id))
			return (int)$id;
		else
			return FALSE; 
	}
	else
		return NULL;
}

function renderQEdit($id) {


}

function renderQtab() {
	global $mysqli, $QT_STR;
	
	$eo = array('even', 'odd');
	$row = 0;
//	$eo = array('odd', 'even');
	
	$qRows = $mysqli->query('SELECT q.id, typ, data2, data, comment, multi, GROUP_CONCAT(t.jmeno SEPARATOR \', \') AS temata FROM (`otazka` AS q INNER JOIN `otazka_tema` AS qt ON q.id = qt.otazka_id) INNER JOIN tema AS t ON t.id = qt.tema_id GROUP BY q.id ORDER BY q.id');
	if (!$qRows) {
		flm("Dotaz na vše z `otazka` selhal.", '', MSG_ERROR);
		return array();
	}
	$out = '<table><tr><th>Číslo</th><th>Typ</th><th>Otázka</th><th>Komentář</th><th>Témata</th><th>Multi</th></tr>';
	
	while($q = $qRows->fetch_object()) {
		$otazka = ($q->typ == 1) ? $q->data : $q->data2;
		$out .= '<tr class="'.$eo[($row++)%2].'"><td class="center"><a href="'.WEB_ROOT.'edit/'.$q->id.'">'.$q->id.'</a></td><td>'.$QT_STR[$q->typ].'</td><td>'.$otazka.'</td><td>'.$q->comment.'</td><td>'.$q->temata.'</td><td>'.($q->multi?'Ano':'Ne').'</td></tr>';
	}
	
	$out .= '<tr class="'.$eo[($row++)%2].'"><td colspan="6" class="center"><a href="'.WEB_ROOT.'edit/new">Přidat novou</a></td></tr>';
	$out .= '</table>';
	return $out;
}



