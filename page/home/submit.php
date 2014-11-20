<?php

require_once PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit:');

if (isset($_POST['submit-seznam'])) {
  clearSet();
	header("Location: http://seznam.cz");
	exit;
}
elseif (isset($_POST['submit-start'])) {
	if (getSetState() == HOME_INIT) {
		setSetState(HOME_TEMA);
		//reset default setParams
	}
}
elseif (isset($_POST['submit-create'])) {
	//prepareRandomSet(3);
	if (getSetState() == HOME_TEMA && isset($_POST['limit']) && $_POST['limit']) {
		//save default setParams
		prepareRandomSet($_POST['limit'], getChosenTema());
	}
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
				setSetState(SADA_READ_ONLY);
				$_SESSION['home']['sada']['pozice'] = 0;
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
	if (getSetState() == SADA_READ_ONLY) {
		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name']))
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
		}
		clearSet();
	}
}
