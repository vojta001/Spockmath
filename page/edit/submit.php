<?php

flm($_POST, '$_POST caught in submit:');
flm($_FILES, '$_FILES caught in submit:');

require_once PHP_PATH.'thumb.php';

if (isset($_FILES['answer']) && !empty($_POST['qid'])) {

	//$uploaddir = IMG_PATH.'a';
	$uploaddir = IMG_FILEPATH.'q/'.$_POST['qid'];

	foreach ($_FILES['answer']['error'] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {

			$uploadfile = $uploaddir.'/'.$_FILES['question']['name'];
			$tmp_name = $_FILES['answer']['tmp_name'][$key];
			$name = $_FILES['answer']['name'][$key];

			/*$saveFileName = 'q/'.$_POST['qid'].'/'.$key.'.'.pathinfo($name, PATHINFO_EXTENSION);
			move_uploaded_file($tmp_name, IMG_PATH.$saveFileName);
			makeThumb($saveFileName);*/

			if (move_uploaded_file($_FILES['answer']['tmp_name'][$key], $uploadfile)) {
				flm ('Povedlo se, soubor "<i>'.$_FILES['question']['name'].'</i>" byl úspěšně nahrán', '', MSG_INFO);
				makeThumb($uploadfile);
			} else {
				flm ('Ups. Soubor '.$_FILES['question']['tmp_name'].' se nepodařilo správně nahrát do '.$uploadfile, '', MSG_ERROR);
			}
		} elseif ($error != UPLOAD_ERR_NO_FILE) {
			flm ('Ups. Soubor (soubory) se nepodařilo správně nahrát', '', MSG_ERROR);
		}
	}
}
if (isset($_FILES['question']) && !empty($_POST['qid'])) {
	if (is_array($_FILES['question']['name'])) {
		flm('Pole $_FILES["question"] má více nebo méně než jeden prvek', '', MSG_ERROR);
		flm ($_FILES['question']);
	} elseif ($_FILES['question']['size'] > 1000000) {
		flm ('Nahrávaný soubor "<i>'.$_FILES['question']['name'].'</i>" je příliš velký. Maximální povolená velikost je 1 MB.', '', MSG_ERROR);
	} else {
		$uploaddir = IMG_FILEPATH.'q/'.$_POST['qid'];
		$uploadfile = $uploaddir.'/'.$_FILES['question']['name'];
		if (move_uploaded_file($_FILES['question']['tmp_name'], $uploadfile)) {
			flm ('Povedlo se, soubor "<i>'.$_FILES['question']['name'].'</i>" byl úspěšně nahrán', '', MSG_INFO);
			makeThumb($uploadfile);
		} elseif ($error != UPLOAD_ERR_NO_FILE) {
			flm ('Ups. Soubor '.$_FILES['question']['tmp_name'].' se nepodařilo správně nahrát do '.$uploadfile, '', MSG_ERROR);
		}
	}
}

