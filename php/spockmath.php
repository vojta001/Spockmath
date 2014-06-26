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
  flm("Sada smazána.", '', MSG_INFO);
}

function setMoveNext() {
  $_SESSION['sada']['pozice']++;
}

function setMovePrev() {
  $_SESSION['sada']['pozice']--;
}

function isSetOpen() {
  return isset($_SESSION['sada']['stav']) && ($_SESSION['sada']['stav'] === SADA_OPEN);
}

function getPosition() {
  return $_SESSION['sada']['pozice'];
}

function getQCount() {
  return count($_SESSION['sada']['otazky']);
}

function getCurrentQ() {
  if (!isSetOpen()) {
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


function saveQPost() {


}

