<?php

if (!loggedIn())
	redirect404();

if (($_id = getIdFromQuery()) === FALSE)
	redirect404();


require PHP_PATH.'spockmath.php';
$cssStyles[] = 'editor.css';

$jsScripts[] = 'jquery.min.js';
$jsScripts[] = 'mathquill.min.js';
$jsScripts[] = 'editor.js';
$cssStyles[] = 'mathquill.css';



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

	$out = '<fieldset id="otazka"><legend>Otázka</legend>';

	//$out .= print_r($q, 1).'<br /><br />'.PHP_EOL;

	$out .= '<input name="qid" type="hidden" value="'.$id.'" />'.PHP_EOL;

	$out .= '<select name="typ" onchange="editorQTypChange(this.value)">';
	foreach($QT_STR as $key => $typ)
		$out .= '<option value="'.$key.'"'.($q['typ']==$key?' selected':'').'>'.$typ.'</option>';
	$out .= '</select>'.PHP_EOL;
	$out .= '<label><input name="multi" type="checkbox" value="multi" '.($q['multi']?'checked ':'').'/>Možno více voleb</label>'.PHP_EOL;
	$out .= '<textarea placeholder="Spockův komentář k otázce" name="comment">'.$q['comment'].'</textarea>'.PHP_EOL;

	$out .= '<div class="qType" id="qType-'.QT_TEXT.'"'.($q['typ']!=QT_TEXT?' style="display: none;"':'').'>';
	$out .= '<textarea placeholder="Text otázky" name="data">'.($q['typ']==QT_TEXT?$q['data']:'').'</textarea>'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="qType" id="qType-'.QT_OBR.'"'.($q['typ']!=QT_OBR?' style="display: none;"':'').'>';
	$out .= '<textarea placeholder="Doplňující text (otázka) k obrázku" name="data2">'.($q['typ']==QT_OBR?$q['data2']:'').'</textarea>'.PHP_EOL;
	$out .= '<img src="'.($q['typ']==QT_OBR? IMG_PATH.'q/resize/'.$q['data'] : '').'" alt="otázka" />';
	$out .= '<input name="question" type="file" />';
	$out .= '</div>';

	$out .= '<div class="qType" id="qType-'.QT_MATH.'"'.($q['typ']!=QT_MATH?' style="display: none;"':'').'>';
	$out .= '<textarea placeholder="Doplňující text (otázka) ke vzorci" name="data2">'.($q['typ']==QT_MATH?$q['data2']:'').'</textarea>'.PHP_EOL;
	$out .= '<span id="mathQuillizedInput" class="mathquill-editable">'.($q['typ']==QT_MATH?$q['data']:'').'</span>'.PHP_EOL;
	$out .= '<input id="deMathQuillizedInput" name="dataMQ" type="hidden" value="" />'.PHP_EOL;
	$out .= '</div>';

	$out .= '</fieldset>';

	return $out;
}


function renderQTemas($id) {
	global $QT_STR;

	$out = '<fieldset id="temata"><legend>Zařazení do témat</legend><ul>';

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

function renderAnswer($id, $typ, $spravna, $data, $data2, $isTemplate = FALSE) {
	global $AT_STR;
	
	$out = '<div id="odpoved-'.$id.'" class="odpoved"'.($isTemplate?' style="display: none;"':'').'>';
	$out .= '<input id="delete-'.$id.'" name="'.$id.'[delete]" type="hidden" value="0" />'.PHP_EOL;
	$out .= '<a class="delete button" onclick="markAForErase(parentNode.id)">X</a>'.PHP_EOL;

	$out .= '<select name="'.$id.'[typ]" onchange="editorATypChange('.$id.', this.value)">';
	foreach($AT_STR as $key => $typeName)
		$out .= '<option value="'.$key.'"'.($typ == $key?' selected':'').'>'.$typeName.'</option>';
	$out .= '</select>'.PHP_EOL;

	$out .= '<label><input name="'.$id.'[spravna]" type="checkbox" value="spravna" '.($spravna?'checked ':'').'/>Správná</label>'.PHP_EOL;

	$out .= '<div class="aType typ-'.AT_TEXT.'"'.($typ != AT_TEXT?' style="display: none;"':'').'>';
	$out .= '<textarea name="'.$id.'[data]" placeholder="Text odpovědi">'.($typ == AT_TEXT?$data:'').'</textarea>'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_OBR.'"'.($typ != AT_OBR?' style="display: none;"':'').'>';
	$out .= '<img src="'.($typ == AT_OBR? IMG_PATH.'a/'.$data : '').'" alt="odpověď" />';
	$out .= '<input name="answer['.$id.']" type="file" />';
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_MATH.'"'.($typ != AT_MATH?' style="display: none;"':'').'>';
	$out .= '<span id="mathQuillizedInput-'.$id.'" class="mathquill-editable">'.($typ == AT_MATH?$data:'').'</span>'.PHP_EOL;
	$out .= '<input id="deMathQuillizedInput-'.$id.'" name="'.$id.'[data]" type="hidden" value="" />'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_EDIT.'"'.($typ != AT_EDIT?' style="display: none;"':'').'>';
	$out .= '<textarea name="'.$id.'[data]">'.$data.'</textarea>'.PHP_EOL;
	$out .= '<textarea name="'.$id.'[data2]">'.$data2.'</textarea>'.PHP_EOL;
	$out .= '</div>';

	$out .= '</div>';
	return $out;
}



function renderAnswers($id) {
	global $mysqli;

	$as = array();

	$sql = 'SELECT a.*, (SELECT COUNT(*) FROM `inst_odpoved` AS ia WHERE ia.aid = a.id) AS pocet_pouziti FROM `odpoved` AS a WHERE a.fid = '.$id;
	$rows = $mysqli->query($sql);
	if (!$rows) {
		flm("Dotaz na vše z `odpoved` selhal.", '', MSG_ERROR);
		return '';
	}

	$out = '<fieldset id="odpovedi"><legend>Odpovědi</legend>';

	$maxId = 0;
	while($a = $rows->fetch_object()) {
		$out .= renderAnswer($a->id, $a->typ, $a->spravna, $a->data, $a->data2);
		if ($maxId < $a->id)
			$maxId = $a->id; 			
	}

	//$out .= renderAnswer('NEXT_ID', AT_TEXT, FALSE, "", "", TRUE);
	//$out .= renderAnswer(-2, AT_TEXT, FALSE, "", "", TRUE);
	//$out .= renderAnswer(-3, AT_TEXT, FALSE, "", "", TRUE);

	$out .= renderAnswer('NEXT_ID', AT_TEXT, FALSE, "", "", TRUE);

	$out .= '<div>';
	$out .= '<a class="button" onclick="editorAddA()">Přidat novou</a>'.PHP_EOL;
	$out .= '</div>';
	$out .= '<input type="hidden" name="nextId" value="'.($maxId + 1).'" />'.PHP_EOL;

	$out .= '</fieldset>';


	return $out;
}


function renderQEdit($id) {
	global $mysqli, $QT_STR;

	$out = '<form method="post" enctype="multipart/form-data" onsubmit="prepareForSubmit();">'.PHP_EOL;

	$out .= renderQInputs($id);
	$out .= renderQTemas($id);
	$out .= renderAnswers($id);

	$out .= '<input type="submit" value="Submit-test" onclick="warnIfDel();" />';
	$out .= '<input type="checkbox" id="deleteQ" name="deleteQ" onclick="markQForErase()" />Smazat celou otázku';

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



