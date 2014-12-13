<?php

require PHP_PATH.'spockmath.php';
$cssStyles[] = 'editor.css';

$jsScripts[] = 'jquery.min.js';
$jsScripts[] = 'mathquill.min.js';
$cssStyles[] = 'mathquill.css';

if (($_id = getIdFromQuery()) === FALSE)
	redirect404();



function redirect404() {
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


function renderQInputs($id) {
	global $mysqli, $QT_STR;

	if ($id) {
		$rows = $mysqli->query('SELECT * FROM otazka WHERE id = '.$id);
		if (!$rows) {
			flm('Otázka s ID '.$id.' nenalezena', '', MSG_ERROR);
			redirect404();
		}
		$q = $rows->fetch_assoc();
	} else {
		$q = array('id' => 0, 'typ' => 1, 'comment' => '', 'data' => '', 'data2' => '', 'multi' => 0);
	}
	
	$out = '<fieldset name="otazka-form"><legend>Otázka</legend>';
	
	//$out .= print_r($q, 1).'<br /><br />'.PHP_EOL; 

	$out .= '<input name="qid" type="hidden" value="'.$id.'" />'.PHP_EOL;

	$out .= '<select name="typ">'; 
	foreach($QT_STR as $key => $typ)
		$out .= '<option value="'.$key.'"'.($q['typ']==$key?'selected':'').'>'.$typ.'</option>';
	$out .= '</select>'.PHP_EOL; 

	$out .= '<textarea name="comment">'.$q['comment'].'</textarea>'.PHP_EOL;

	switch($q['typ']) {
		case QT_TEXT:
			$out .= '<textarea name="data">'.$q['data'].'</textarea>'.PHP_EOL;
			break;
		case QT_OBR:
			$out .= '<img src="'.IMG_PATH.'q/'.$q['data'].'" alt="otázka" />';
			break;
		case QT_MATH:
			$out .= '<span class="mathquill-editable" name="data">'.$q['data'].'</span>'.PHP_EOL;
			break;
		default:
			flm('Není definována šablona pro tento typ otázky ('.$q['typ'].')', '', MSG_ERROR);
	}

	$out .= '<textarea name="data2">'.$q['data2'].'</textarea>'.PHP_EOL;
	$out .= '<label><input name="multi" type="checkbox" value="multi" '.($q['multi']?'checked ':'').'/>Možno více voleb</label>'.PHP_EOL;

	$out .= '</fieldset>';

	return $out;
}


function renderQTemas($id) {
	global $QT_STR;
	
	$out = '<fieldset name="temata"><legend>Zařazení do témat</legend><ul>';

	$predmet = '';
	foreach (getTemas($id) as $tema) {
		if ($predmet != $tema->p_jmeno) {
			if ($predmet)
	      $out .= '</ul></li>';

      $predmet = $tema->p_jmeno;
      $out .= '<li><span class="predmet">'.$predmet.'</span><ul>';
		}

		$out .= '<li><label><input type="checkbox" name="tema-'.$tema->id.'" '.($tema->cnt?'checked ':'').'/>'.htmlspecialchars($tema->jmeno).'</label><div class="description">'.htmlspecialchars($tema->komentar).'</div></li>';
	}
	if ($predmet)
    $out .= '</ul></li>';

	$out .= '</ul></fieldset>';

	return $out;
}


function renderAnswers($id) {
	global $mysqli, $AT_STR;
	
	$as = array();
	
	$sql = 'SELECT a.*, (SELECT COUNT(*) FROM `inst_odpoved` AS ia WHERE ia.aid = a.id) AS pocet_pouziti FROM `odpoved` AS a WHERE a.fid = '.$id;	
	$rows = $mysqli->query($sql);
	if (!$rows) {
		flm("Dotaz na vše z `odpoved` selhal.", '', MSG_ERROR);
		return '';
	}

	$out = '<fieldset name="odpovedi"><legend>Odpovědi</legend>';
	
	while($a = $rows->fetch_object()) {
		$out .= '<div>';
		$out .= '<input name="delete-'.$a->id.'" type="hidden" value="0" />'.PHP_EOL;
		$out .= '<a class="delete button" onclick="alert(\'TODO: js, nastaví delete-ID na 1 a skryje tento div\')">X</a>'.PHP_EOL;

		$out .= '<select name="typ-'.$a->id.'">'; 
		foreach($AT_STR as $key => $typ)
			$out .= '<option value="'.$key.'">'.$typ.'</option>';
		$out .= '</select>'.PHP_EOL; 

		$out .= '<textarea name="data-'.$a->id.'">'.$a->data.'</textarea>'.PHP_EOL;
		$out .= '<textarea name="data2-'.$a->id.'">'.$a->data2.'</textarea>'.PHP_EOL;

		$out .= '<label><input name="spravna-'.$a->id.'" type="checkbox" value="spravna" '.($a->spravna?'checked ':'').'/>Správná</label>'.PHP_EOL;
		$out .= '</div>';
	}

	$out .= '<div>';
	$out .= '<a class="button" onclick="alert(\'TODO: js, který sem přidá jeden div\')">Přidat novou</a>'.PHP_EOL;
	$out .= '</div>';
	
	$out .= '</fieldset>';

	return $out;
}


function renderQEdit($id) {
	global $mysqli, $QT_STR;
	
	$out = '<form method="post">'.PHP_EOL;

	$out .= renderQInputs($id);
	$out .= renderQTemas($id);
	$out .= renderAnswers($id);

	$out .= '<input type="submit" value="Submit-test">';	
	
	$out .= '</form>'.PHP_EOL;
	return $out;
}


function renderQtab() {
	global $mysqli, $QT_STR;
	
	$eo = array('even', 'odd');
	$row = 0;
	
	$sql = 'SELECT q.*, (SELECT GROUP_CONCAT(t.jmeno SEPARATOR \', \') FROM `otazka_tema` AS qt INNER JOIN tema AS t ON t.id = qt.tema_id WHERE q.id = qt.otazka_id) AS temata, (SELECT COUNT(*) FROM `odpoved` AS a WHERE q.id = a.fid) AS pocet_odpovedi, (SELECT COUNT(*) FROM `inst_otazka` AS io WHERE q.id = io.qid) AS pocet_zodpovezeni FROM `otazka` AS q';	
	$qRows = $mysqli->query($sql);
	if (!$qRows) {
		flm("Dotaz na vše z `otazka` selhal.", '', MSG_ERROR);
		return '';
	}
	$out = '<table><tr><th>Číslo</th><th>Typ</th><th>Otázka</th><th>Komentář</th><th>Témata</th><th>Odpov.</th><th>Multi</th><th>Zodpo.</th></tr>';
	
	while($q = $qRows->fetch_object()) {
		$otazka = ($q->typ == 1) ? $q->data : $q->data2;
		$out .= '<tr class="'.$eo[($row++)%2].'"><td class="center"><a class="button" href="'.WEB_ROOT.'edit/'.$q->id.'">'.$q->id.'</a></td><td>'.$QT_STR[$q->typ].'</td><td>'.$otazka.'</td><td>'.$q->comment.'</td><td>'.$q->temata.'</td><td>'.$q->pocet_odpovedi.'</td><td>'.($q->multi?'Ano':'Ne').'</td><td>'.$q->pocet_zodpovezeni.'</td></tr>';
	}
	
	$out .= '<tr class="'.$eo[($row++)%2].'"><td colspan="8" class="center"><a class="button" href="'.WEB_ROOT.'edit/new">Přidat novou</a></td></tr>';
	$out .= '</table>';
	return $out;
}



