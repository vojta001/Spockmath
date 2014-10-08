<?php

require PHP_PATH.'spockmath.php';

$jsScripts[] = JS_PATH.'jquery.min.js';
$jsScripts[] = JS_PATH.'mathquill.min.js';
$cssStyles[] = CSS_PATH.'mathquill.css';

function addDecimalCodes() {
  global $jsScripts, $cssStyles;
	$q = getCurrentQ();
	$raise = FALSE;
	foreach ($q->answer as $a) {
		if ($a->typ == AT_EDIT) {
			$raise = TRUE;
			break;
		}
	}
	if ($raise) {
		$jsScripts[] = JS_PATH.'validatedecimals.js';
		$cssStyles[] = CSS_PATH.'editsanimations.css';
	}
}

addDecimalCodes();


function spockSay($text, $left = FALSE) {
	$out = '';
	if ($left)
		$out .= '<img src="img/design/leonard-right.png" ale="Hlava Spocka" />'.PHP_EOL
		.'<p class="spock-bubble left">'.$text.'</p>';
	else
		$out .= '<p class="spock-bubble right">'.$text.'</p>'.PHP_EOL
			.'<img src="img/design/leonard-left.png" ale="Hlava Spocka" />';
	return '<div class="spock-say">'.$out.'</div>';
}


function renderOtazka($q) {
	switch ($q->typ) {
		case QT_TEXT:
			$x = '<p>'.$q->data.'</p>';
			break;
		case QT_OBR:
			$x = '<p>'.$q->data2.'</p><img src="'.IMG_PATH.'q/'.$q->data.'" alt="otázka" />';
			break;
		case QT_MATH:
			$x = '<p>'.$q->data2.'</p><span class="mathquill-embedded-latex">'.$q->data.'</span>';
			break;
		default:
			flm('Není definováno chování pro otázku typu '.$q->typ, '', MSG_ERROR);
			$x = '<p class="error">Zobrazení není definováno!</p>';
			break;
	}
	return '<div class="otazka">'.$x.'</div>';
}


function renderOdpoved($q) {
	$out = '';
	$i = 0;
	foreach ($q->answer as $a) {
		$i++;
		switch ($a->typ) {
			case AT_TEXT:
				$x = '<span>'.$a->data.'</span>';
				break;
			case AT_OBR:
				$x = '<img src="'.IMG_PATH.'a/'.$a->data.'" alt="otázka" />';
				break;
			case AT_MATH:
				$x = '<span class="mathquill-embedded-latex">'.$a->data.'</span>';
				break;
			case AT_EDIT:
				$x = '<span>'.$a->data.'</span><input class="decimalTextBox" type="text" name="edit-'.$i.'" value="'.$a->odpovedDecimal.'" autocomplete="off" />';
				break;
			default:
				flm('Není definováno chování pro odpověď typu '.$a->typ, '', MSG_ERROR);
				flm (print_r ($a, 1));
				$x = '<p class="error">Zobrazení není definováno!</p>';
				break;
		}
		if ($q->answer[$i-1]->selected == 1)
			$checked = 'checked="checked"';
		else
			$checked = '';

		if ($q->multi)
			$out .= '<li><input type="checkbox" name="'.$i.'" '.$checked.'/>'.$x.'</li>';
		else
			$out .= '<li><input type="radio" name="moznost" value="'.$i.'" '.$checked.'/>'.$x.'</li>';
	}

	return '<div class="odpoved"><ul>'.$out.'</ul></div>';
}


function renderQ() {
	$q = getCurrentQ();
	$qn = getCurrentQnum();
	$out = renderotazka($q);
	$out .= renderOdpoved($q);
	$out .= '<input type="hidden" name="hash" value="'.getSetHash().'" /><input type="hidden" name="qnum" value="'.$qn.'" />';
	return '<div class="priklad">'.$out.'</div>';
}