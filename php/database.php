<?php

define('DB_OTAZKA', 'otazka');
define('DB_SADA', 'sada');
define('DB_TEMA', 'tema');
define('DB_PREDMET', 'predmet');
define('DB_ODPOVED', 'odpoved');

$mysqli = new mysqli("localhost", "root", "", "spockmath");

if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$mysqli->set_charset('utf8');

function getQImageName($id) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$result = $mysqli->query('SELECT * FROM `otazka` WHERE `id`='.$safeId)->fetch_object();
	if ($result->typ == QT_OBR)
		return $result->data;
	else
		return false;
}

function getAImageName($fid, $id) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$safeFid = $mysqli->escape_string($fid);
	$result = $mysqli->query('SELECT * FROM `odpoved` WHERE `fid`='.$safeFid.' AND `id`='.$safeId)->fetch_object();
	if ($result->typ == AT_OBR)
		return $result->data;
	else
		return false;
}

function getRowCount($table) {
	global $mysqli;
	$table = $mysqli->escape_string($table);
	return ($count = $mysqli->query('SELECT COUNT(*) AS cnt FROM `'.$table.'`')) ? $count->fetch_object()->cnt : false;
}

function getQCountDB() {
	global $mysqli;
	$rows = getRowCount(DB_OTAZKA);

	if (!$rows) {
		flm("Woe, nemam asi tabulku `otazka`!<br />Takhle to nebubde fungovat!", '', MSG_ERROR);
		return 0;
	}

	return $rows;
}

function getHashDB($username) {
	global $mysqli;

	$safeUsr = $mysqli->escape_string($username);
	$result = $mysqli->query('SELECT passwd FROM `login` WHERE `usr`="'.$safeUsr.'";');

	$hash = $result->fetch_object()->passwd;
	flm($hash, 'Hash retrieved from DB');
	return $hash;
}

function getDBUsers() {
	global $mysqli;

	$result = $mysqli->query('SELECT * FROM `login`');

	$users = array();
	while ($obj = $result->fetch_object())
		$users[] = $obj;

	return $users;
}


function getRandomQsDB($count, $temas) {
	global $mysqli;

	$Qrows = $mysqli->query('SELECT o.* FROM `otazka` AS o JOIN `otazka_tema` AS ot ON o.id = ot.otazka_id WHERE ot.tema_id IN ('.implode(',', $temas).') GROUP BY o.id ORDER BY rand() LIMIT '.$count);
	if (!$Qrows) {
		flm("Ti povidam, že nemam `otazka`!", '', MSG_ERROR);
		return array();
	}

	$Qs = array();
	while ($Q = $Qrows->fetch_object()) {
		
		$Arows = $mysqli->query('SELECT * FROM `odpoved` WHERE `fid` = '.$Q->id.' ORDER BY rand()');
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

/**
 * @id id uložené sady
 * @return pole otázek s jejich odpověďmi včetně stavu
 */

function getSadaQ($id) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$Qrows = $mysqli->query('SELECT q.*, iq.id AS iqid FROM sada AS s JOIN inst_otazka AS iq ON s.id = iq.sid JOIN otazka AS q ON q.id = iq.qid WHERE s.id = '.$safeId.' ORDER BY q.id');
	if (!$Qrows) {
		flm("Ti povidam, že nemam `otazka`!", '', MSG_ERROR);
		return array();
	}

	$Qs = array();
	while ($Q = $Qrows->fetch_object()) {

		//$Arows = $mysqli->query('SELECT * FROM `odpoved` AS a LEFT JOIN inst_odpoved AS ia ON a.id = ia.aid WHERE a.fid = '.$Q->id.' AND (ia.iqid = '.$Q->iqid.' OR iq.id IS NULL)');
		$Arows = $mysqli->query('SELECT a.*, ia.data AS odpovedDecimal FROM odpoved AS a LEFT JOIN inst_odpoved AS ia ON (a.id = ia.aid AND ia.iqid = '.$Q->iqid.') WHERE a.fid = '.$Q->id.' ORDER BY a.id');
		$Q->answer = array();
		while ($A = $Arows->fetch_object()){
			$A->selected = !is_null($A->odpovedDecimal);
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

	$sadaId = $mysqli->insert_id;
	$_SESSION['home']['sada']['dbId'] = $sadaId;

	$arr = $_SESSION['home']['sada']['otazky'];

	foreach($arr as $q) {
		$retVal = $mysqli->query('INSERT INTO `inst_otazka` (`sid`, `qid`) VALUES ('.$sadaId.', '.$q->id.')');
		if ($retVal !== TRUE) {
			flm('Štestí kámo, nejde mi uložit int_otazka. Že tys to hackoval?', '', MSG_ERROR);
			return FALSE;
		}
		$iqId = $mysqli->insert_id;

		foreach($q->answer as $a) if ($a->selected) {
			if (isset($a->odpovedDecimal))
				$data = $a->odpovedDecimal;
			else
				$data = 0;

			$sql = 'INSERT INTO `inst_odpoved` (`iqid`, `aid`, `data`) VALUES ('.$iqId.', '.$a->id.', '.$data.')';
			$retVal = $mysqli->query($sql);
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
	if (isset($_SESSION['home']['sada']['dbId']) && is_numeric($_SESSION['home']['sada']['dbId']) && $_SESSION['home']['sada']['dbId'] > 0)
		$id = $_SESSION['home']['sada']['dbId'];
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

function getTemas($qId = 0) {
	global $mysqli;
	
	$qId = (int)$qId;
	
	if ($qId > 0)
		$sql = 'SELECT p.jmeno AS p_jmeno, t.*, (ot.otazka_id IS NOT NULL) AS cnt FROM `tema` AS t JOIN `predmet` AS p ON t.`pid` = p.`id` LEFT JOIN `otazka_tema` AS ot ON (t.`id` = ot.`tema_id` AND ot.`otazka_id` = '.$qId.') GROUP BY t.`id` ORDER BY t.`pid`';
	else
		$sql = 'SELECT p.jmeno AS p_jmeno, t.*, (SELECT COUNT(*) FROM `otazka_tema` AS ot WHERE ot.tema_id = t.id) AS cnt FROM `tema` AS t JOIN `predmet` AS p ON t.pid = p.id GROUP BY t.id ORDER BY t.`pid`';

	$retValue = $mysqli->query($sql);
	$tema = array();
	while ($obj = $retValue->fetch_object()) {
	$tema[] = $obj;
	}
	return $tema;
}

function getAnsweredSets($name) {
	global $mysqli;

	$safeName = $mysqli->escape_string($name);
	$result = $mysqli->query('SELECT * FROM sada WHERE jmeno="'.$safeName.'" ORDER BY datum');

	$out = array();
	while ($obj = $result->fetch_object())
		$out[] = $obj;

	return $out;
}
