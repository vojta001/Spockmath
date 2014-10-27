<?php

require_once PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit:');

if (isset($_POST['submit-seznam'])) {
  header("Location: http://seznam.cz");
  exit;
}
elseif (isset($_POST['submit-create'])) {
  //prepareRandomSet(3);
	if (!isSetOpen() || !isSetRead-Only())
  	prepareDebugSet();
}
elseif (isset($_POST['submit-next'])) {
	if (isSetOpen())
		if (saveQPost())
  		setMoveNext();
}
elseif (isset($_POST['submit-prev'])) {
	if (isSetOpen())
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
  if (isSetOpen())
		if(saveQPost())
  		if (sadaSave()) {
      	flm("Sada uložena.", '', MSG_INFO);
    		//clearSet();
        $_SESSION['sada']['stav'] = SADA_READ_ONLY;
        $_SESSION['sada']['pozice'] = 1;
			}
}
elseif (isset($_POST['submit-next-ro'])) {
	if (isSetReadOnly())
  		setMoveNext();
}
elseif (isset($_POST['submit-prev-ro'])) {
	if (isSetReadOnly())
    	setMovePrev();
}
elseif (isset($_POST['submit-save-ro'])) {
  if (isSetReadOnly())
    		clearSet();
}
