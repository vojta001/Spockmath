<?php


define('IMG_404', IMG_PATH.'sys/404.png');

/**
 * Zajistí existenci thumbnailu a vrací jeho cestu (webovou)
 * Thumbnail má stejnou cestu, název i _příponu_
 *
 * @param $fileName - cesta k souboru relativně vůči IMG_FILEPATH (vč. přípony)
 * @return - webová adresa thumbnailu nebo FALSE, pokud originál souboru neexistuje
 */
function makeThumb($fileName) {
	$webFileName = IMG_R_PATH.$fileName;

	//check thumbnail exists
	$thumbFileName = IMG_R_FILEPATH.$fileName;
	if (file_exists($thumbFileName))
		return $webFileName;

	//create thumbnail
	$origFileName = IMG_FILEPATH.$fileName;
	if (!file_exists($origFileName))
		return FALSE;

	$resizePath = dirname($thumbFileName);
	if (!file_exists($resizePath))
		mkdir($resizePath, 0777, true);
	$img = new Imagick();
	$img->readImage($origFileName);
	$img->scaleImage(380, 380, true);
	$img->writeImage($thumbFileName);
	$img->clear();
	return $webFileName;
}

/**
 * Přeloží dotaz na konktérní název souboru (název souboru a příponu čerpá z DB)
 * @param $idtota - ID otázky
 * @return - webová adresa thumbnailu (předá z makeThumb)
 */
function makeQuestionThumb($id) {
	//fetch fileName
	$origFileName = 'q/'.$id.'/'.getQImageName($id);   //return makeThumb('q/'.$q.'/'.$a -- z DB!!!);
//flm($origFileName);

	if (($thumbFileName = makeThumb($origFileName)) === FALSE) {
		$thumbFileName = IMG_404;
	}

	return $thumbFileName;
}

/**
 * Přeloží dotaz na konktérní název souboru (název souboru a příponu čerpá z DB)
 * @param $fid - ID otázky
 * @param $id - ID odpovědi
 * @return - webová adresa thumbnailu (předá z makeThumb)
 */
function makeAnswerThumb($fid, $id) {
	//fetch fileName
	$origFileName = 'q/'.$fid.'/'.getAImageName($fid, $id);   //return makeThumb('q/'.$q.'/'.$a -- z DB!!!);
	flm($origFileName);
	if (($thumbFileName = makeThumb($origFileName)) === FALSE) {
		$thumbFileName = IMG_404;
	}

	return $thumbFileName;
}
