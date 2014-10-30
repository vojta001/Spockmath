<?php

$mysqli = new mysqli("localhost", "root", "", "spockmath");

if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$mysqli->set_charset('utf8');


function getQCountDB() {
	global $mysqli;
	$rows = $mysqli->query('SELECT COUNT(*) AS cnt FROM `otazka`');

	if (!$rows) {
		flm("Woe, nemam asi tabulku `otazka`!<br />Takhle to nebubde fungovat!", '', MSG_ERROR);
		return 0;
	}

	return $rows->fetch_object()->cnt;
}


function getRandomQsDB($count) {
	global $mysqli;

	$Qrows = $mysqli->query('SELECT * FROM `otazka` ORDER BY rand() LIMIT '.$count);
	if (!$Qrows) {
		flm("Ti povidam, že namem `otazka`!", '', MSG_ERROR);
		return array();
	}

	$Qs = array();
	while ($Q = $Qrows->fetch_object()) {
		
		$Arows = $mysqli->query('SELECT * FROM `odpoved` WHERE `fid` = '.$Q->id.' ORDER BY rand()');
		$Q->answer = array();			
		while ($A = $Arows->fetch_object()){
			$A->selected = 0;
			$A->odpovedDecimal = '';
			$Q->answer[] = $A;
		}
		$Qs[] = $Q;	
	}		
	return $Qs;
}


function getAllQsDB() {
	global $mysqli;

	$Qrows = $mysqli->query('SELECT * FROM `otazka` ORDER BY `id`');
	if (!$Qrows) {
		flm("Ti povidam, že nemam `otazka`!", '', MSG_ERROR);
		return array();
	}

	$Qs = array();
	while ($Q = $Qrows->fetch_object()) {
		
		$Arows = $mysqli->query('SELECT * FROM `odpoved` WHERE `fid` = '.$Q->id.' ORDER BY `id`');
		$Q->answer = array();			
		while ($A = $Arows->fetch_object()){
			$A->selected = 0;
			if ($A->typ == AT_EDIT) $A->odpovedDecimal = '';
			$Q->answer[] = $A;
		}
		$Qs[] = $Q;	
	}		
	return $Qs;
}

function sadaSave() {
	global $mysqli;
	$name = '';

	$retVal = $mysqli->query('INSERT INTO `sada` (`jmeno`) VALUES (\''.$name.'\')');

	if ($retVal !== TRUE) {
		flm('Štestí kámo, nejde mi to uložit. Že tys to hackoval?', '', MSG_ERROR);
		return FALSE;
	}

	$sadaId =  $mysqli->insert_id;
	$_SESSION['sada']['dbId'] = $sadaId;

	$arr = $_SESSION['sada']['otazky'];
	//flm($arr,'otazky...');

	foreach($arr as $q) {
		$retVal = $mysqli->query('INSERT INTO `inst_otazka` (`sid`, `qid`) VALUES ('.$sadaId.', '.$q->id.')');
		if ($retVal !== TRUE) {
			flm('Štestí kámo, nejde mi uložit int_otazka. Že tys to hackoval?', '', MSG_ERROR);
			return FALSE;
		}
		$iqId =  $mysqli->insert_id;

		foreach($q->answer as $a) if ($a->selected) {
			if (isset($a->odpovedDecimal)) $data = $a->odpovedDecimal;
			else $data = 0;

			$retVal = $mysqli->query('INSERT INTO `inst_odpoved` (`iqid`, `aid`, `data`) VALUES ('.$iqId.', '.$a->id.', '.$data.')');
			if ($retVal !== TRUE) {
				flm('Štestí kámo, nejde mi uložit int_odpoved. Že tys to hackoval?', '', MSG_ERROR);
				return FALSE;
			}

		}
	}
	return TRUE;
}

function updateSadaName($name) {
	global $mysqli;

  $secName = $mysqli->escape_string($name);
	if (isset($_SESSION['sada']['dbId']) && is_numeric($_SESSION['sada']['dbId']) && $_SESSION['sada']['dbId'] > 0)
		$id = $_SESSION['sada']['dbId'];
	else {
		flm('Jujda, nemám ID tvojí sady! Takhle to jméno neuložím', '', MSG_ERROR);
		return false;
	}

	$retVal = $mysqli->query('UPDATE `sada` SET `jmeno` = "'.$secName.'" WHERE id='.$id);

	if ($retVal !== TRUE) {
		flm('Hmmm, nějak mi to nejde uložit, asi budeš muset zůstat beze jména.', '', MSG_ERROR);
		return FALSE;
	}
	return true;
}