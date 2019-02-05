<?php

define('DB_OTAZKA', 'otazka');
define('DB_SADA', 'sada');
define('DB_TEMA', 'tema');
define('DB_ODPOVED', 'odpoved');

$mysqli = new mysqli("localhost", "root", "", "spockmath");

if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$mysqli->set_charset('utf8');

function getQImageName($id) {
	global $mysqli;

	$safeId = (int)$id;
	$result = $mysqli->query('SELECT * FROM `otazka` WHERE `id`='.$safeId)->fetch_object();
	if ($result->typ == QT_OBR)
		return $result->data;
	else
		return false;
}

function getAImageName($fid, $id) {
	global $mysqli;

	$safeId = (int)$id;
	$safeFid = (int)$fid;
	$sql = 'SELECT * FROM `odpoved` WHERE `fid`='.$safeFid.' AND `id`='.$safeId;
	$result = $mysqli->query($sql)->fetch_object();
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

function getSubjectCount() {
	global $mysqli;
	return ($count = $mysqli->query('SELECT COUNT(*) AS cnt FROM tema WHERE pid IS NULL')) ? $count->fetch_object()->cnt : false;
}

function getQCountDB() {
	global $mysqli;
	$rows = getRowCount(DB_OTAZKA);

	if (!$rows) {
		flm("Woe, nemam asi tabulku `otazka`!<br />Takhle to nebude fungovat!", '', MSG_ERROR);
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

function getDBUser($user) {
	global $mysqli;

	$safeUser = $mysqli->escape_string($user);
	$result = $mysqli->query('SELECT * FROM `login` where usr ="'.$safeUser.'"');
	return $result->fetch_object();
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

	if ($retVal !== true) {
		flm('Štestí kámo, nejde mi to uložit. Že tys to hackoval?', '', MSG_ERROR);
		return false;
	}

	$sadaId = $mysqli->insert_id;
	$_SESSION['home']['sada']['dbId'] = $sadaId;

	$arr = $_SESSION['home']['sada']['otazky'];

	foreach($arr as $q) {
		$retVal = $mysqli->query('INSERT INTO `inst_otazka` (`sid`, `qid`) VALUES ('.$sadaId.', '.$q->id.')');
		if ($retVal !== true) {
			flm('Štestí kámo, nejde mi uložit int_otazka. Že tys to hackoval?', '', MSG_ERROR);
			return false;
		}
		$iqId = $mysqli->insert_id;

		foreach($q->answer as $a) if ($a->selected) {
			if (isset($a->odpovedDecimal))
				$data = $a->odpovedDecimal;
			else
				$data = 0;

			$sql = 'INSERT INTO `inst_odpoved` (`iqid`, `aid`, `data`) VALUES ('.$iqId.', '.$a->id.', '.$data.')';
			$retVal = $mysqli->query($sql);
			if ($retVal !== true) {
				flm('Štestí kámo, nejde mi uložit int_odpoved. Že tys to hackoval?', '', MSG_ERROR);
				return false;
			}

		}
	}
	return true;
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

	if ($retVal !== true) {
		flm('Hmmm, nějak mi to nejde uložit, asi budeš muset zůstat beze jména.', '', MSG_ERROR);
		return false;
	}
	return true;
}

function getTemas($qId = 0) {
	global $mysqli;
	
	$qId = (int)$qId;
	
	if ($qId > 0)
		$sql = 'SELECT t.*,
(SELECT COUNT(*) FROM `otazka_tema` AS ot WHERE ot.tema_id = t.id) AS usage_count,
EXISTS (SELECT * FROM `otazka_tema` AS ot WHERE ot.tema_id = t.id AND ot.otazka_id = '.$qId.') AS checked
FROM `tema` AS t';
	else
		$sql = 'SELECT t.*,
(SELECT COUNT(*) FROM `otazka_tema` AS ot WHERE ot.tema_id = t.id) AS usage_count
FROM `tema` AS t';

	$retValue = $mysqli->query($sql);
	$tema = array();
	while ($obj = $retValue->fetch_object()) {
		$tema[$obj->id] = $obj;
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

function deleteQDB($id) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$sql = 'DELETE FROM otazka WHERE id='.$safeId;
	return $mysqli->query($sql);
}

function deleteADB($id, $fid) {
	global $mysqli;


	$safeId = $mysqli->escape_string($id);
	$safeFid = $mysqli->escape_string($fid);

	$sql = 'DELETE FROM odpoved WHERE fid = '.$safeFid.' AND id = '.$safeId;
	return $mysqli->query($sql);
}

function updateQ($id, $typ, $comment, $data, $data2, $multi) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$safeTyp = 'typ = '.$mysqli->escape_string($typ);
	$safeComment = 'comment = "'.$mysqli->escape_string($comment).'"';
	$safeData = ($data !== null)?('data = "'.$mysqli->escape_string($data).'", '):'';
	$safeData2 = 'data2 = "'.$mysqli->escape_string($data2).'"';
	$safeMulti = 'multi = '.(int)$multi;
	$sql = "UPDATE otazka SET $safeTyp, $safeComment, $safeData$safeData2, $safeMulti WHERE id = $safeId";
	return $mysqli->query($sql);
}

function updateA($id, $fid, $typ, $data, $data2, $spravna) {
	global $mysqli;

	$safeId = $mysqli->escape_string($id);
	$safeFid = $mysqli->escape_string($fid);
	$safeTyp = 'typ = '.$mysqli->escape_string($typ);
	$safeComment = 'comment = "'.$mysqli->escape_string($comment).'"';
	$safeData = 'data = "'.$mysqli->escape_string($data).'"';
	$safeData2 = 'data2 = "'.$mysqli->escape_string($data2).'"';
	$safeSpravna = 'spravna = '.(int)$spravna;
	return $mysqli->query("UPDATE odpoved SET $safeTyp, $safeData, $safeData2, $safeSpravna WHERE id = $safeId AND fid = $safeFid");
}

function getQuestionTableRows() {
	global $mysqli;

	$sql = 'SELECT q.*, (SELECT GROUP_CONCAT(t.jmeno SEPARATOR \', \') FROM `otazka_tema` AS qt INNER JOIN tema AS t ON t.id = qt.tema_id WHERE q.id = qt.otazka_id) AS temata, (SELECT COUNT(*) FROM `odpoved` AS a WHERE q.id = a.fid) AS pocet_odpovedi, (SELECT COUNT(*) FROM `inst_otazka` AS io WHERE q.id = io.qid) AS pocet_zodpovezeni FROM `otazka` AS q';
	$qRows = $mysqli->query($sql);
	if (!$qRows) {
		flm("Dotaz na vše z `otazka` selhal.", '', MSG_ERROR);
		return '';
	}

	$rows = array();
	while($q = $qRows->fetch_object()) {
		$rows[] = $q;
	}
	return $rows;
}

function questionExists($id) {
	global $mysqli;

	if (is_int($id)) {
		$sql = 'SELECT EXISTS(SELECT * FROM otazka WHERE id = '.$id.') AS found';
		$rows = $mysqli->query($sql);
		return $rows->fetch_object()->found;
	} else
		return false;
}

function answerExists($id, $fid) {
	global $mysqli;

	if (is_int($id) && is_int($fid)) {
		$sql = 'SELECT * FROM odpoved WHERE id = '.$id.' AND fid = '.$fid;
		$rows = $mysqli->query($sql);
		return $rows->fetch_object();
	} else {
		return false;
	}
}

function temaExists($id) {
	global $mysqli;

	if (is_int($id)) {
		$sql = 'SELECT EXISTS(SELECT * FROM tema WHERE id = '.$id.') AS found';
		$rows = $mysqli->query($sql);
		return $rows->fetch_object()->found;
	} else
		return false;
}

function insertQ($typ, $comment, $data, $data2, $multi) {
	global $mysqli;

	$safeTyp = '"'.$mysqli->escape_string($typ).'"';
	$safeComment = '"'.$mysqli->escape_string($comment).'"';
	$safeData = '"'.$mysqli->escape_string($data).'"';
	$safeData2 = '"'.$mysqli->escape_string($data2).'"';
	$safeMulti = (int)$multi;



	if ($mysqli->query("INSERT INTO otazka (typ, comment, data, data2, multi) VALUES ($safeTyp, $safeComment, $safeData, $safeData2, $safeMulti)"))
		return $mysqli->insert_id;
	else
		return false;
}

function insertA($id, $fid, $typ, $data, $data2, $spravna) {
	global $mysqli;

	$safeId = (int)$id;
	$safeFid = (int)$fid;
	$safeTyp = (int)$typ;
	$safeData = '"'.$mysqli->escape_string($data).'"';
	$safeData2 = '"'.$mysqli->escape_string($data2).'"';
	$safeSpravna = (int)$spravna;
	if ($mysqli->query("INSERT INTO odpoved (id, fid, typ, data, data2, spravna) VALUES ($safeId, $safeFid, $safeTyp, $safeData, $safeData2, $safeSpravna)"))
		return $mysqli->insert_id;
	else
		return false;
}

function updateQTemas($qid, $replaceTemaIds, $deleteTemaIds) {
	global $mysqli;

	$safeQid = (int)$qid;
	$values = array();
	foreach($replaceTemaIds as $id) {
		$values[] = '('.$safeQid.','.$id.')';
	}
	$mysqli->query('REPLACE INTO otazka_tema (otazka_id, tema_id) VALUES '.implode(', ', $values));

	$mysqli->query('DELETE FROM otazka_tema WHERE otazka_id = '.$safeQid.' AND tema_id IN ('.implode(', ', $deleteTemaIds).')');
}
