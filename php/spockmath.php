<?php

define('QT_TEXT', 1);
define('QT_OBR', 2);
define('QT_MATH', 3);

define('AT_TEXT', 1);
define('AT_OBR', 2);
define('AT_MATH', 3);
define('AT_EDIT', 4);

define('SADA_OPEN', 1);
define('SADA_CLOSED', 2);
define('SADA_READ_ONLY', 3);


function prepareRandomSet($count) {
	if (isset($_SESSION['sada'], $_SESSION['sada']['stav']) && $_SESSION['sada']['stav'] == SADA_OPEN) {
		flm("Sada už byla otevřená!");
		return;
	}

	if ($count < 1) {
		flm("Minimální velikost sady je 1 otázka!", '', MSG_ERROR);
		return;
	}

	$totalQs = getQCountDB();
	if ($count > $totalQs) {
		flm("Žádáš větší sadu, než je otázek!", '', MSG_ERROR);
		return;
	}

	if (!isset($_SESSION['sada']))
		$_SESSION['sada'] = array();

	$_SESSION['sada']['otazky'] = getRandomQsDB($count);
	$_SESSION['sada']['pozice'] = 0;
	$_SESSION['sada']['stav'] = SADA_OPEN;
	$_SESSION['sada']['hash'] = hash('crc32', print_r($_SESSION['sada']['otazky'], 1));

	flm("Nová sada vytvořena.", '', MSG_INFO);
}

function prepareDebugSet() {
	if (isset($_SESSION['sada'], $_SESSION['sada']['stav']) && $_SESSION['sada']['stav'] == SADA_OPEN) {
		flm("Sada už byla otevřená!");
		return;
	}

	if (!isset($_SESSION['sada']))
		$_SESSION['sada'] = array();

	$_SESSION['sada']['otazky'] = getAllQsDB($count);
	$_SESSION['sada']['pozice'] = 0;
	$_SESSION['sada']['stav'] = SADA_OPEN;
	$_SESSION['sada']['hash'] = hash('crc32', print_r($_SESSION['sada']['otazky'], 1));

	flm("Nová sada vytvořena.", '', MSG_INFO);
}

function clearSet() {
	unset($_SESSION['sada']);
}

function setMoveNext() {
	$_SESSION['sada']['pozice']++;
}

function setMovePrev() {
	$_SESSION['sada']['pozice']--;
}

function getSetState() {
	if (isset($_SESSION['sada']['stav']))
		return ($_SESSION['sada']['stav']);
	else
		return false;
}

function getPosition() {
	return $_SESSION['sada']['pozice'];
}

function getQCount() {
	return count($_SESSION['sada']['otazky']);
}

function getCurrentQ() {
	if (!getSetState() == SADA_OPEN && !getSetState() == SADA_READ_ONLY) {
		flm('Nedam ti aktuální otázku! Není otevřená sada!', '', MSG_ERROR);
		return;
	}

	$current = $_SESSION['sada']['pozice'];
	$count = count($_SESSION['sada']['otazky']);
	if ($current < 0 || $current >= $count) {
		flm("Jsmem mimo rozsah sady! ($current/$count)", '', MSG_WARNING);
		$current = max(0, min($count-1, $current));
	}

	return $_SESSION['sada']['otazky'][$current];
}

function getCurrentQnum() {
	return $_SESSION['sada']['pozice'];
}

function getSetHash() {
	return $_SESSION['sada']['hash'];
}

function saveSingleAnswer() {
	$q = getCurrentQ();

	$selected = $_POST['moznost'];
	if (isset($selected) && is_numeric($selected) && ($selected > 0)) {
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
	if (isset($_POST['qnum']) && is_numeric($_POST['qnum']) && $_POST['qnum'] >= 0 && $_POST['qnum'] < count($_SESSION['sada']['otazky']))
		return $_POST['qnum'];
	else
		return FALSE;
}

function checkHash() {
	return $_POST['hash'] && $_POST['hash'] == getSetHash();
}

function validatePostEdit() {
	$count = count($_SESSION['sada']['otazky'][$_POST['qnum']]->answer);

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
	$count = count($_SESSION['sada']['otazky'][$_POST['qnum']]->answer);

	for ($i = 1; $i <= $count; $i++) {
		if (isset($_POST['edit-'.$i])) {
			$text = $_POST['edit-'.$i];
			$text = preg_replace('/\s+/', '', $text);
			$num = str_replace(',', '.', $text);
			if (is_numeric($num) || !$text)
			$_SESSION['sada']['otazky'][$_POST['qnum']]->answer[$i-1]->odpovedDecimal = $num;
		}
	}
}


function saveQuestion() {
	if ($_SESSION['sada']['otazky'][$_POST['qnum']]->multi) {
		saveMultiAnswer();
	} else {
		saveSingleAnswer();
	}

	saveEdits();
}


/**
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
