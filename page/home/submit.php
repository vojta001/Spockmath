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
				setSetState(SADA_REG);
				//$_SESSION['home']['sada']['pozice'] = 0;
			}
}
elseif (isset($_POST['submit-reg'])) {
	if (getSetState() == SADA_REG) {
		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name'])) {
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
				setSetState(SADA_SCORE);
			}
		} else
			flm('Uveď prosím své jméno nebo přezdívku!', '', MSG_WARNING);
	}
}
elseif (isset($_POST['submit-next-ro'])) {
	if (getSetState() == SADA_READ_ONLY) {
		if (getPosition() < getQCount()-1)
			setMoveNext();
		else
			setSetState(SADA_SCORE);
	}
}
elseif (isset($_POST['submit-prev-ro'])) {
	if (getSetState() == SADA_READ_ONLY) {
		if (getPosition() > 0)
			setMovePrev();
		else
			setSetState(SADA_SCORE);;

		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name']))
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
		}
	}
}
elseif (isset($_POST['submit-save-ro'])) {
	if (getSetState() == SADA_READ_ONLY) {
		setSetState(SADA_SCORE);
		if (isset ($_POST['name']) && is_string($_POST['name']) && trim($_POST['name'])) {
			if (updateSadaName($_POST['name']))
				flm('Nastavil jsem ti jméno na: "'.$_POST['name'].'"', '', MSG_INFO);
		}
	}
}
elseif (isset($_POST['submit-end'])) {
	if (getSetState(SADA_REG))
		flm('Konec', '', MSG_INFO);
		clearSet();
}
elseif (isset($_POST['submit-score'])) {
	if (getSetState() == SADA_READ_ONLY) {
		setSetState(SADA_SCORE);
	}
}
elseif (isset($_POST['submit-walk'])) {
	if (getSetState() == SADA_SCORE) {
		setSetState(SADA_READ_ONLY);
		setPosition(0);
	}
}
