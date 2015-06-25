<?php

require PHP_PATH.'spockmath.php';
require PHP_PATH.'thumb.php';

$jsScripts[] = 'jquery.min.js';
$jsScripts[] = 'mathquill.min.js';
$cssStyles[] = 'mathquill.css';

function addDecimalCodes() {
	global $jsScripts, $cssStyles;
	if (getSetState() == SADA_OPEN) {
		$q = getCurrentQ();
		$raise = FALSE;
		foreach ($q->answer as $a) {
			if ($a->typ == AT_EDIT) {
			$raise = TRUE;
			break;
			}
		}
		if ($raise) {
			$jsScripts[] = 'validatedecimals.js';
			$cssStyles[] = 'editsanimations.css';
		}
	}
}

addDecimalCodes();


function renderOtazka($q) {
	switch ($q->typ) {
		case QT_TEXT:
			$x = '<p>'.$q->data.'</p>';
			break;
		case QT_OBR:
			$x = '<p>'.$q->data2.'</p><img src="'.makeQuestionThumb($q->id).'" alt="otázka" />';
			break;
		case QT_MATH:
			$x = '<p>'.$q->data2.'</p><span class="mathquill-embedded-latex">'.$q->data.'</span>';
			break;
		default:
			flm('Není definováno chování pro otázku typu '.$q->typ, '', MSG_ERROR);
			$x = '<p class="error">Zobrazení není definováno!</p>';
			break;
	}
	return '<div id="otazka">'.$x.'</div>';
}


function renderOdpoved($q) {
	$out = '';
	$i = 0;
	$rightA = '';
	$readOnly = (getSetState() == SADA_READ_ONLY) ? 'disabled="disabled" ' : '';
	$itemClass = '';
	$cssEdit = '';
	foreach ($q->answer as $a) {
		$i++;
		$rightA = '';
		$editVal = (($a->typ == AT_EDIT) ? $a->odpovedDecimal : '');
		if (getSetState() == SADA_READ_ONLY) {
			$itemClass = 'class="';

			if ($a->spravna) {
				$itemClass .= 'right';
				if ($a->typ == AT_EDIT && $a->data2 != $a->odpovedDecimal) {
					$rightA = 'Správná: ';
					$rightA .= $a->data2 + 0;
				}
			}
			elseif (!$a->spravna && $a->selected)
				$itemClass .= 'wrong';

			if ($a->typ == AT_EDIT) {
				$cssEdit = ' ';
				if ($a->selected) {
					if ($a->data2 == $a->odpovedDecimal && $a->spravna)
						$cssEdit .= 'right';
					else
						$cssEdit .= 'wrong';
				}
				elseif ($a->spravna) {
					//$editVal = $a->data2 + 0;
					$cssEdit .= 'shouldbe';
				}
			}

			$itemClass .= '"';
		}
		switch ($a->typ) {
			case AT_TEXT:
				$x = '<span>'.$a->data.'</span>';
				break;
			case AT_OBR:
				$x = '<img src="'.makeAnswerThumb($a->fid, $a->id).'" alt="otázka" />';
				break;
			case AT_MATH:
				$x = '<span class="mathquill-embedded-latex">'.$a->data.'</span>';
				break;
			case AT_EDIT:
				$x = '<span>'.$a->data.'</span><input class="decimalTextBox'.$cssEdit.'" type="text" name="edit-'.$i.'" value="'.$editVal.'" '.$readOnly.'autocomplete="off" />'.$rightA;
				break;
			default:
				flm('Není definováno chování pro odpověď typu '.$a->typ, '', MSG_ERROR);
				flm ($a);
				$x = '<p class="error">Zobrazení není definováno!</p>';
				break;
		}

		$checked = ($q->answer[$i-1]->selected == 1)?'checked="checked" ':'';

		if ($q->multi)
			$out .= PHP_EOL.'  <li '.$itemClass.'><input type="checkbox" name="'.$i.'" '.$readOnly.$checked.'/>'.$x.'</li>';
		else
			$out .= PHP_EOL.'  <li '.$itemClass.'><input type="radio" name="moznost" value="'.$i.'" '.$readOnly.$checked.'/>'.$x.'</li>';
	}

	return '<div id="odpoved"><ul>'.$out.PHP_EOL.'</ul></div>';
}


function renderQ() {
	$q = getCurrentQ();
	$qn = getCurrentQnum();
	$out = renderotazka($q).PHP_EOL;
	$out .= renderOdpoved($q);
	$out .= '<input type="hidden" name="hash" value="'.getSetHash().'" />'.PHP_EOL.'<input type="hidden" name="qnum" value="'.$qn.'" /><hr class="clearfix" />';
	return '<div class="priklad">'.$out.'</div>';
}


function renderSpockQuestion() {
	$comment = getCurrentQ()->comment;
	return jarSay("Zde je otázka ".(getPosition()+1)." z ".getQCount().($comment?'<br />'.$comment:''), JAR_SPOCK, FALSE);
}

function renderSetParams(){
	$out = '';

	$predmet = '';

	$out .= renderTemasTree(getTemasTree(), false);
	//zohlednit defaultSetParams
	$out .= '<div id="limit"><span>Velikost sady</span><input type="number" name="limit" min="1" max="30" value="10" step="1" /></div>';

	return '<div id="setparams">'.$out.'</div><hr class="clearfix" />';
}

function isRightQ($q) {
	if ($q->multi) {
		foreach ($q->answer as $a)
			if ($a->selected != $a->spravna)
				return false;
		return true;
	}
	else {
		foreach ($q->answer as $a)
			if ($a->selected)
				return $a->spravna;
		return false;
	}
}

function renderScore() {
	$out = '';

	$i = 0;
	foreach ($_SESSION['home']['sada']['otazky'] as $q) {
		$i++;
		if (isRightQ($q))
			$class = 'right';
		else
			$class = 'wrong';

		$out .= '<li class="'.$class.'">Otázka číslo:'.$i.'</li>';
	}

	return '<div id="score"><ul>'.$out.'</ul></div>';
}
