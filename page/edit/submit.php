<?php

flm($_POST, '$_POST caught in submit:');

function makeThumbnail() {
	return true;
}

if (isset($_FILES['answer']) && !empty($_POST['id'])) {

	$uploaddir = IMG_PATH.'a';

    foreach ($_FILES['answer']['error'] as $key => $error) {
    	if ($error == UPLOAD_ERR_OK) {
        	$tmp_name = $_FILES['answer']['tmp_name'][$key];
        	$name = $_FILES['answer']['name'][$key];
			$saveFileName = 'q/'.$_POST['id'].'/'.$key.'.'.pathinfo($name, PATHINFO_EXTENSION);
	        move_uploaded_file($tmp_name, IMG_PATH.$saveFileName);
			makeThumbnail($saveFileName);
	    } else {
	        flm ('Ups. Soubor (soubory) se nepodařilo správně nahrát');
		}
	}
}
if (isset($_FILES['question']) && !empty($_POST['id'])) {
	if (count($_FILES['question']['name']) != 1) {
		global $release;
		if ($release == RELEASE_DEGUB) {
			flm('Pole $_FILES["question"] má více nebo méně než jeden prvek');
			flm ($_FILES['question'])
		}
	} elseif ($_FILES['question']['size'] > 1000000) {
		flm ('Nahrávaný soubor "<i>'.$_FILES['question']['name'].'</i>" je příliš velký. Maximální povolená velikost je 1 MB.');
	} else {
		$uploaddir = IMG_PATH.'q/original/';
		$uploadfile = $uploaddir.'test.png';
		if (move_uploaded_file($_FILES['question']['tmp_name'], $uploadfile)) {
    		flm ('Povedlo se, soubor "<i>'.$_FILES['question']['name'].'</i>" byl úspěšně nahrán');
		} else {
    		flm ('Ups. Soubor (soubory) se nepodařilo správně nahrát');
		}
	}
}

