<?php

require_once PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit:');

if (isset($_POST['submit-seznam'])) {
	header("Location: http://seznam.cz");
	exit;
}
elseif (isset($_POST['submit-create'])) {
	//prepareRandomSet(3);
	if (!getSetState() == SADA_OPEN || !getSetState() == READ_ONLY)
		prepareDebugSet();
}
elseif (isset($_POST['submit-next'])) {
	if (getSetState() == SADA_OPEN)
		if (saveQPost())
			setMoveNext();
}
elseif (isset($_POST['submit-prev'])) {
	if (getSetState() == SADA_OPEN)
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
	if (getSetState() == SADA_OPEN)
		if(saveQPost())
			if (sadaSave()) {
				flm("Sada uložena.", '', MSG_INFO);
				//clearSet();
				$_SESSION['sada']['stav'] = SADA_READ_ONLY;
				$_SESSION['sada']['pozice'] = 0;
			}
}
elseif (isset($_POST['submit-next-ro'])) {
	if (getSetState() == SADA_READ_ONLY) {
		setMoveNext();

		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name']))
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
		}
	}
}
elseif (isset($_POST['submit-prev-ro'])) {
	if (getSetState() == SADA_READ_ONLY) {
		setMovePrev();

		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name']))
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
		}
	}
}
elseif (isset($_POST['submit-save-ro'])) {
	if (getSetState() == SADA_READ_ONLY)
		clearSet();
}
