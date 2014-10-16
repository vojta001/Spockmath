<?php

require_once PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit:');

if (isset($_POST['submit-seznam'])) {
  header("Location: http://seznam.cz");
  exit;
}
elseif (isset($_POST['submit-create'])) {
  //prepareRandomSet(3);
  prepareDebugSet();
}
elseif (isset($_POST['submit-next'])) {
	if (saveQPost())
  	setMoveNext();
}
elseif (isset($_POST['submit-prev'])) {
	if (saveQPost())
    setMovePrev();
}
elseif (isset($_POST['submit-clear'])) {
  clearSet();
  flm("Sada smazána.", '', MSG_INFO);
}
elseif (isset($_POST['submit-save'])) {
//TODO: místo toho uložit odpovědi do tabulek, které ještě neexistují
  //flm('Sorry, tahle funkce ještě není implementována (bude-li vůbec).', '', MSG_ERROR);

	if(saveQPost())
  	if (sadaSave()) {
      flm("Sada uložena.", '', MSG_INFO);
    	clearSet();
		}
}
