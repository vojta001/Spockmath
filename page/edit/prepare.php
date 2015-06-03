<?php

require_once PHP_PATH.'thumb.php';

if (!loggedIn())
	redirect404();

/**
 * returns 0 to create new question, FALSE to indicate error, NULL to show index table
 */
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

//used in render
$_id = getIdFromQuery();


if ($_id === FALSE || (($_id > 0 || $_id < 0) && !questionExists($_id)))
	redirect404();


require PHP_PATH.'spockmath.php';
$cssStyles[] = 'editor.css';

$jsScripts[] = 'jquery.min.js';
$jsScripts[] = 'mathquill.min.js';
$jsScripts[] = 'editor.js';
$cssStyles[] = 'mathquill.css';
$jsScripts[] = 'validatedecimals.js';

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

	$out .= '<div class="qType'.($q['typ']!=QT_TEXT?' hiddenToBeDeleted':'').'" id="qType-'.QT_TEXT.'">';
	$out .= '<textarea placeholder="Text otázky" name="data">'.($q['typ']==QT_TEXT?$q['data']:'').'</textarea>'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="qType'.($q['typ']!=QT_OBR?' hiddenToBeDeleted':'').'" id="qType-'.QT_OBR.'">';
	$out .= '<textarea placeholder="Doplňující text (otázka) k obrázku" name="data2">'.($q['typ']==QT_OBR?$q['data2']:'').'</textarea>'.PHP_EOL;
	$out .= '<img src="'.($q['typ']==QT_OBR? makeQuestionThumb($id) : '').'" alt="otázka" />';
	$out .= '<input name="question" type="file" />';
	$out .= '</div>';

	$out .= '<div class="qType'.($q['typ']!=QT_MATH?' hiddenToBeDeleted':'').'" id="qType-'.QT_MATH.'">';
	$out .= '<textarea placeholder="Doplňující text (otázka) ke vzorci" name="data2">'.($q['typ']==QT_MATH?$q['data2']:'').'</textarea>'.PHP_EOL;
	$out .= '<span id="mathQuillizedInput" class="mathquill-editable">'.($q['typ']==QT_MATH?$q['data']:'').'</span>'.PHP_EOL;
	$out .= '<input id="deMathQuillizedInput" name="data" type="hidden" value="testval" />'.PHP_EOL;
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

		$out .= '<li><label><input type="checkbox" name="tema['.$tema->id.']" '.($tema->cnt?'checked ':'').'/>'.htmlspecialchars($tema->jmeno).'</label><div class="description">'.htmlspecialchars($tema->komentar).'</div></li>';
	}
	if ($predmet)
	$out .= '</ul></li>';

	$out .= '</ul></fieldset>';

	return $out;
}

function renderAnswer($id, $qid, $typ, $spravna, $data, $data2, $isTemplate = FALSE) {
	global $AT_STR;

	$out = '<div id="odpoved-'.$id.'" class="odpoved"'.($isTemplate?' style="display: none;"':'').'>';
	$out .= '<input id="delete-'.$id.'" name="answer['.$id.'][delete]" type="hidden" value="0" />'.PHP_EOL;
	$out .= '<a class="delete button" onclick="markAForErase(parentNode.id)">X</a>'.PHP_EOL;

	$out .= '<select name="answer['.$id.'][typ]" onchange="editorATypChange(parentNode.id, this.value)">';
	foreach($AT_STR as $key => $typeName)
		$out .= '<option value="'.$key.'"'.($typ == $key?' selected':'').'>'.$typeName.'</option>';
	$out .= '</select>'.PHP_EOL;

	$out .= '<label><input name="answer['.$id.'][spravna]" type="checkbox" value="spravna" '.($spravna?'checked ':'').'/>Správná</label>'.PHP_EOL;

	$out .= '<div class="aType typ-'.AT_TEXT.($typ != AT_TEXT?' hiddenToBeDeleted':'').'">';
	$out .= '<textarea name="answer['.$id.'][data]" placeholder="Text odpovědi">'.($typ == AT_TEXT?$data:'').'</textarea>'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_OBR.($typ != AT_OBR?' hiddenToBeDeleted':'').'">';
	$out .= '<img src="'.($typ == AT_OBR? makeAnswerThumb($qid, $id) : '').'" alt="odpověď" />';
	$out .= '<input name="answer['.$id.']" type="file" />';
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_MATH.($typ != AT_MATH?' hiddenToBeDeleted':'').'">';
	$out .= '<span id="mathQuillizedInput-'.$id.'" class="mathquill-editable">'.($typ == AT_MATH?$data:'').'</span>'.PHP_EOL;
	$out .= '<input id="deMathQuillizedInput-'.$id.'" name="answer['.$id.'][data]" type="hidden" value="" />'.PHP_EOL;
	$out .= '</div>';

	$out .= '<div class="aType typ-'.AT_EDIT.($typ != AT_EDIT?' hiddenToBeDeleted':'').'">';
	$out .= '<textarea name="answer['.$id.'][data]">'.$data.'</textarea>'.PHP_EOL;
	$out .= '<textarea class= "decimalTextBox" name="answer['.$id.'][data2]">'.$data2.'</textarea>'.PHP_EOL;
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
		$out .= renderAnswer($a->id, $id, $a->typ, $a->spravna, $a->data, $a->data2);
		if ($maxId < $a->id)
			$maxId = $a->id;
	}

	//$out .= renderAnswer('NEXT_ID', AT_TEXT, FALSE, "", "", TRUE);
	//$out .= renderAnswer(-2, AT_TEXT, FALSE, "", "", TRUE);
	//$out .= renderAnswer(-3, AT_TEXT, FALSE, "", "", TRUE);

	$out .= renderAnswer('NEXT_ID', $id, AT_TEXT, FALSE, "", "", TRUE);

	$out .= '<div>';
	$out .= '<a class="button" onclick="editorAddA()">Přidat novou</a>'.PHP_EOL;
	$out .= '</div>';
	$out .= '<input type="hidden" name="nextId" value="'.($maxId + 1).'" />'.PHP_EOL;

	$out .= '</fieldset>';


	return $out;
}


function renderQEdit($id) {
	$out = '<form method="post" enctype="multipart/form-data">'.PHP_EOL;

	$out .= renderQInputs($id);
	$out .= renderQTemas($id);
	$out .= renderAnswers($id);

	$out .= '<input type="submit" name="save" value="Uložit" onclick="prepareForSubmit();" /> ';
	$out .= '<input type="button" name="delete" value="Smazat" onclick="if (confirm(\'Opravdu chete smazat otázku? (Tato akce nemůže být vrácena zpět)\')) {$(this).after(\'<input type=&quot;hidden&quot; name=&quot;delete&quot; value=&quot;1&quot; />\'); submit();}" />';

	$out .= '</form>'.PHP_EOL;
	return $out;
}


function renderQtab() {
	global $QT_STR;

	$rows = getQuestionTableRows();

	$eo = array('even', 'odd');

	$out = '<table><tr><th>Číslo</th><th>Typ</th><th>Otázka</th><th>Komentář</th><th>Témata</th><th>Odpov.</th><th>Multi</th><th>Zodpo.</th></tr>';

	$i = 0;
	foreach($rows as $q) {
		$otazka = ($q->typ == 1) ? $q->data : $q->data2;
		$out .= '<tr class="'.$eo[($i++)%2].'"><td class="center"><a class="button" href="'.WEB_ROOT.'edit/'.$q->id.'">'.$q->id.'</a></td><td>'.$QT_STR[$q->typ].'</td><td>'.$otazka.'</td><td>'.$q->comment.'</td><td>'.$q->temata.'</td><td>'.$q->pocet_odpovedi.'</td><td>'.($q->multi?'Ano':'Ne').'</td><td>'.$q->pocet_zodpovezeni.'</td></tr>';
	}

	$out .= '<tr class="'.$eo[($i++)%2].'"><td colspan="8" class="center"><a class="button" href="'.WEB_ROOT.'edit/new">Přidat novou</a></td></tr>';
	$out .= '</table>';
	return $out;
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		rmdir($dir);
	}
}

function deleteQ($id) {
	$safeId = (int)$id;

	deleteQDB($safeId);

	if ($safeId) {
		rrmdir(IMG_FILEPATH.'q/'.$safeId);
		rrmdir(IMG_R_FILEPATH.'q/'.$safeId);
	}
}

function deleteA($id, $fid) {
	$safeId = (int)$id;
	$safeFid = (int)$fid;

	deleteADB($safeId, $safeFid);

	if ($safeId) {
		rrmdir(IMG_FILEPATH.'q/'.$safeFid.'/'.$id.'.png');
		rrmdir(IMG_R_FILEPATH.'q/'.$safeFid.'/'.$id.'.png');
	}
}

function getScrewdFileName($id) {
	return hash('crc32', $id);
}
