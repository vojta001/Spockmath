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
    flm("Ti povidam, že namem `otazka`!", '', MSG_ERROR);
    return array();
  }
  
  $Qs = array();
	while ($Q = $Qrows->fetch_object()) {
		
		$Arows = $mysqli->query('SELECT * FROM `odpoved` WHERE `fid` = '.$Q->id.' ORDER BY `id`');
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