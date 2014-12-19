<?php

require_once PHP_PATH.'database.php';

$jsScripts[] = 'jquery.min.js';


function renderStats() {
	$out = '';
	$out .= '<li>'.getRowCount(DB_OTAZKA).' otázek</li>';
  $out .= '<li>'.getRowCount(DB_ODPOVED).' odpovědí</li>';
	$out .= '<li>'.getRowCount(DB_TEMA).' témat</li>';
	$out .= '<li>'.getRowCount(DB_PREDMET).' předmětů</li>';
	return '<ul class=stats><li>Celkem je tu:<ul>'.$out.'</ul></li></ul>';
}

function renderNameEdit() {

}

