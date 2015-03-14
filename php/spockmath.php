<?php

define('QT_TEXT', 1);
define('QT_OBR', 2);
define('QT_MATH', 3);
$QT_STR = array(1 => 'text', 2 => 'obrázek', 3 => 'vzorec');

define('AT_TEXT', 1);
define('AT_OBR', 2);
define('AT_MATH', 3);
define('AT_EDIT', 4);
$AT_STR = array(1 => 'text', 2 => 'obrázek', 3 => 'vzorec', 4 => 'ruční');

define('HOME_INIT', 1);
define('HOME_TEMA', 2);
define('SADA_OPEN', 3);
define('SADA_READ_ONLY', 4);
define('SADA_REG', 5);
define('SADA_SCORE', 6);


//$cssStyles[] = CSS_PATH.'spockmath.css';

function getChosenTema() {
	$temas = array();

	foreach (getTemas() as $tema) {
		if (isset($_POST['tema-'.$tema->id])) $temas[] = $tema->id;
	}

	return $temas;
}

function prepareRandomSet($count, $temas) {
	if (isset($_SESSION['home']['sada']) && (getSetState() == SADA_OPEN || getSetState() == SADA_READ_ONLY)) {
		flm("Sada už byla otevřená!", '', MSG_WARNING);
		return false;
	}

	if ($count < 1) {
		flm("Minimální velikost sady je 1 otázka!", '', MSG_WARNING);
		return false;
	}

	$totalQs = getQCountDB();
	if ($count > $totalQs) {
		flm("Žádáš větší sadu, než je otázek!", '', MSG_WARNING);
		return false;
	}

	if (!isset($_SESSION['home']['sada']))
		$_SESSION['home']['sada'] = array();

	if (!getChosenTema()) {
		flm('Nejdřív si vyber téma', '', MSG_WARNING);
		return false;
	}

	$_SESSION['home']['sada']['otazky'] = getRandomQsDB($count, $temas);
	$_SESSION['home']['sada']['pozice'] = 0;
	setSetState(SADA_OPEN);
	$_SESSION['home']['sada']['hash'] = hash('crc32', print_r($_SESSION['home']['sada']['otazky'], 1));

	flm("Nová sada vytvořena.", '', MSG_INFO);
	return true;
}

function prepareDebugSet() {
	if (isset($_SESSION['home']['sada']) && (getSetState() == SADA_OPEN || getSetState() == SADA_READ_ONLY)) {
		flm("Sada už byla otevřená!", '', MSG_WARNING);
		return;
	}

	if (!isset($_SESSION['home']['sada']))
		$_SESSION['home']['sada'] = array();

	$_SESSION['home']['sada']['otazky'] = getAllQsDB($count);
	$_SESSION['home']['sada']['pozice'] = 0;
	setSetState(SADA_OPEN);
	$_SESSION['home']['sada']['hash'] = hash('crc32', print_r($_SESSION['home']['sada']['otazky'], 1));

	flm("Nová sada vytvořena.", '', MSG_INFO);
}

function prepareSetById($id) {
	if (isset($_SESSION['home']['sada']) && (getSetState() == SADA_OPEN || getSetState() == SADA_READ_ONLY)) {
		flm("Sada už byla otevřená!", '', MSG_WARNING);
		return false;
	}

	if (!isset($_SESSION['home']['sada']))
		$_SESSION['home']['sada'] = array();

	$_SESSION['home']['sada']['ucitel'] = true;
	$_SESSION['home']['sada']['otazky'] = getSadaQ($id);
	$_SESSION['home']['sada']['pozice'] = 0;
	setSetState(SADA_SCORE);
	$_SESSION['home']['sada']['hash'] = hash('crc32', print_r($_SESSION['home']['sada']['otazky'], 1));
flm($_SESSION['home']['sada']['otazky']);
	flm('Načetl jsem sadu', '', MSG_INFO);
}

function clearSet() {
	unset($_SESSION['home']['sada']);
	setSetState(HOME_INIT);
}

function setMoveNext() {
	$_SESSION['home']['sada']['pozice']++;
}

function setMovePrev() {
	$_SESSION['home']['sada']['pozice']--;
}

function getSetState() {
	if (!isset($_SESSION['home']['stav']))
		$_SESSION['home']['stav'] = HOME_INIT;

	return ($_SESSION['home']['stav']);
}

function setSetState($state){
	if (isset($_SESSION['home']['stav']))
		$_SESSION['home']['stav'] = $state;
	else
		return false;
}

function getPosition() {
	return $_SESSION['home']['sada']['pozice'];
}

function setPosition($pos) {
	$_SESSION['home']['sada']['pozice'] = $pos;
}

function getQCount() {
	return count($_SESSION['home']['sada']['otazky']);
}

function getCurrentQ() {
	if (!getSetState() == SADA_OPEN && !getSetState() == SADA_READ_ONLY) {
		flm('Nedam ti aktuální otázku! Není otevřená sada!', '', MSG_ERROR);
		return;
	}

	$current = $_SESSION['home']['sada']['pozice'];
	$count = count($_SESSION['home']['sada']['otazky']);
	if ($current < 0 || $current >= $count) {
		flm("Jsmem mimo rozsah sady! ($current/$count)", '', MSG_WARNING);
		$current = max(0, min($count-1, $current));
	}

	return $_SESSION['home']['sada']['otazky'][$current];
}

function getCurrentQnum() {
	return $_SESSION['home']['sada']['pozice'];
}

function getSetHash() {
	return $_SESSION['home']['sada']['hash'];
}

function saveSingleAnswer() {
	$q = getCurrentQ();

	if (isset($_POST['moznost']) && is_numeric($selected = $_POST['moznost']) && ($selected > 0)) {
		//clear all answer selections
		foreach ($q->answer as &$a)
			$a->selected = 0;

		$q->answer[$selected-1]->selected = 1;
	}
}

function saveMultiAnswer() {
	$q = getCurrentQ();
	$i = 0;
	foreach ($q->answer as &$a){
		$i++;
		$a->selected = isset($_POST[$i]);
	}
}

function getPostQnum() {
	if (isset($_POST['qnum']) && is_numeric($_POST['qnum']) && $_POST['qnum'] >= 0 && $_POST['qnum'] < count($_SESSION['home']['sada']['otazky']))
		return $_POST['qnum'];
	else
		return FALSE;
}

function checkHash() {
	return $_POST['hash'] && $_POST['hash'] == getSetHash();
}

function validatePostEdit() {
	$count = count($_SESSION['home']['sada']['otazky'][$_POST['qnum']]->answer);

	$isOk = TRUE;
	for ($i = 1; $i <= $count; $i++) {
		if (isset($_POST['edit-'.$i])) {
			$text = $_POST['edit-'.$i];
			$num = str_replace(',', '.', $text);
			$num = preg_replace('/\s+/', '', $num);
			$editOk = (is_numeric($num) || !$text);
			if (!$editOk)
				flm("Do pole odpovědi č. $i zadej číselnou hodnotu!", '', MSG_ERROR);
			$isOk &= $editOk;
		}
	}
	return $isOk;
}


function saveEdits() {
	$count = count($_SESSION['home']['sada']['otazky'][$_POST['qnum']]->answer);

	for ($i = 1; $i <= $count; $i++) {
		if (isset($_POST['edit-'.$i])) {
			$text = $_POST['edit-'.$i];
			$text = preg_replace('/\s+/', '', $text);
			$num = str_replace(',', '.', $text);
			if (is_numeric($num) || !$text)
			$_SESSION['home']['sada']['otazky'][$_POST['qnum']]->answer[$i-1]->odpovedDecimal = $num;
		}
	}
}


function saveQuestion() {
	if ($_SESSION['home']['sada']['otazky'][$_POST['qnum']]->multi) {
		saveMultiAnswer();
	} else {
		saveSingleAnswer();
	}

	saveEdits();
}


/*
 * return value = success
 */
function saveQPost() {
	if (!checkHash() || ($q = getPostQnum()) === FALSE)
		return FALSE;

	if (!validatePostEdit())
		return FALSE;

	saveQuestion();

	return TRUE;
}
