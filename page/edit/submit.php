<?php

require_once 'prepare.php';

if (isset($_POST['delete']) && !empty($_POST['qid'])) {
	deleteQ($_POST['qid']);
	header('HTTP/1.1 303 See Other');
	header("Location: //$_SERVER[SERVER_NAME]".WEB_ROOT.'edit');
	exit;
}
elseif (isset($_POST['save']) && isset($_POST['qid']) && !empty($_POST['typ'])) {
	if ($_POST['qid'] == 0) {
		$qid = insertQ($_POST['typ'], $_POST['comment'], $data, $_POST['data2'], isset($_POST['multi']));
	}
	else {
		$qid = (int)$_POST['qid'];
	}

	$uploadFileBare = null;
	$uploadDirRel = 'q/'.$qid;
	$uploadDir = IMG_FILEPATH.$uploadDirRel;

	if (isset($_FILES['question']) && $_POST['typ'] == QT_OBR) {
		if (is_array($_FILES['question']['name'])) {
			flm('Pole $_FILES["question"] má více nebo méně než jeden prvek', '', MSG_ERROR);
			flm ($_FILES['question']);
		} elseif ($_FILES['question']['size'] > 1000000) {
			flm ('Nahrávaný soubor "<i>'.$_FILES['question']['name'].'</i>" je příliš velký. Maximální povolená velikost je 1 MB.', '', MSG_ERROR);
		} elseif ($_FILES['question']['error'] != UPLOAD_ERR_NO_FILE) {
			$uploadFileBare = getScrewdFileName($qid).'.'.pathinfo($_FILES['question']['name'], PATHINFO_EXTENSION);
			$uploadFileRel = $uploadDirRel.'/'.$uploadFileBare;
			$uploadFile = IMG_FILEPATH.$uploadFileRel;
			if (!file_exists($uploadDir))
				mkdir($uploadDir, 0777, true);

			if (move_uploaded_file($_FILES['question']['tmp_name'], $uploadFile)) {
				flm ('Povedlo se, soubor "<i>'.$_FILES['question']['name'].'</i>" byl úspěšně nahrán.', '', MSG_INFO);
			} elseif ($_FILES['question']['error'] != UPLOAD_ERR_NO_FILE) {
				flm ('Ups. Soubor '.$_FILES['question']['tmp_name'].' se nepodařilo správně nahrát do '.$uploadFile, '', MSG_ERROR);
			}
		}
	}
	$Adata = array();
	if (isset($_FILES['answer'])) {
		if (!file_exists($uploadDir))
			mkdir($uploadDir, 0777, true);
		foreach ($_FILES['answer']['error'] as $key => $error) {
			if ($_POST['answer'][$key]['typ'] != AT_OBR)
				continue;
			if ($error == UPLOAD_ERR_OK) {
				$name = $_FILES['answer']['name'][$key];
				$uploadFileABare = getScrewdFileName($key).'.'.pathinfo($name, PATHINFO_EXTENSION);
				$uploadFileRel = $uploadDirRel.'/'.$uploadFileABare;
				$uploadFile = IMG_FILEPATH.$uploadFileRel;
				$tmp_name = $_FILES['answer']['tmp_name'][$key];
				if (move_uploaded_file($tmp_name, $uploadFile)) {
					flm ('Povedlo se, soubor "<i>'.$name.'</i>" byl úspěšně nahrán', '', MSG_INFO);
					$Adata[$key] = $uploadFileABare;
				} else {
					flm ('Ups. Soubor '.$_FILES['question']['tmp_name'].' se nepodařilo správně nahrát do '.$uploadFile, '', MSG_ERROR);
				}
			} elseif ($error != UPLOAD_ERR_NO_FILE) {
				flm ('Ups. Soubor (soubory) se nepodařilo správně nahrát', '', MSG_ERROR);
			}
		}
	}
	$data = (($_POST['typ'] != QT_OBR)?$_POST['data']:$uploadFileBare);
	updateQ($qid, $_POST['typ'], $_POST['comment'], $data, $_POST['data2'], isset($_POST['multi']));

	if (!$qid) {
		flm('Nepodařilo se vložit otázku do databáze. Pokuste se o úkon znovu a pokud by se incident opakoval, nahlašte to prosím administrátorovi.', '', MSG_ERROR);
		return;
	}
	if (!empty($_POST['answer'])) {
		foreach ($_POST['answer'] as $id => $a) {
			if ($id === 'NEXT_ID')
				continue;

			$data = isset($Adata[$id])?$Adata[$id]:$a['data'];
			$data2 = $a['typ'] != AT_EDIT?'':preg_replace('/\s+/', '', str_replace(',', '.', $a['data2']));
			if (($answer = answerExists($id, $qid)) != null) {
				if ($a['delete'])
					deleteA($id, $qid);
				else {
					if ($answer->typ != AT_OBR || $data)
						updateA($id, $qid, $a['typ'], $data, $data2, isset($a['spravna']));
				}
			} else {
				if (!$a['delete'])
					insertA($id, $qid, $a['typ'], $data, $data2, isset($a['spravna']));
			}
		}
	}
	$temas = getTemas();
	$postTemaIds = array_keys($_POST['tema']);
	$replaceTemaIds = array();
	$deleteTemaIds = array();

	foreach ($temas as $tema) {
		$id = $tema->id;
		if (in_array($id, $postTemaIds))
			$replaceTemaIds[] = $id;
		else
			$deleteTemaIds[] = $id;
	}
	updateQTemas($qid, $replaceTemaIds, $deleteTemaIds);
}
