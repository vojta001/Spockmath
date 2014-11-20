<?php

require PHP_PATH.'spockmath.php';

$jsScripts[] = JS_PATH.'jquery.min.js';
$jsScripts[] = JS_PATH.'mathquill.min.js';
$cssStyles[] = CSS_PATH.'mathquill.css';

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
			$jsScripts[] = JS_PATH.'validatedecimals.js';
			$cssStyles[] = CSS_PATH.'editsanimations.css';
		}
	}
}

addDecimalCodes();


function spockSay($text, $left = FALSE) {
	$out = '';
	if ($left)
		$out .= '<img src="img/design/leonard-right.png" alt="Hlava Spocka" />'
		.'<p class="spock-bubble left">'.$text.'</p>';
	else
		$out .= '<p class="spock-bubble right">'.$text.'</p>'
			.'<img src="img/design/leonard-left.png" alt="Hlava Spocka" />';
	return '<div class="spock-say">'.$out.'</div>'.PHP_EOL;
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
	return '<div id="otazka">'.$x.'</div>';
}


function renderOdpoved($q) {
	$out = '';
	$i = 0;
  $rightA = '';
	if (getSetState() == SADA_READ_ONLY)
		$readOnly = 'disabled="disabled"';
	else
		$readOnly = '';
	$cssClass = '';
	$cssEdit = '';
	foreach ($q->answer as $a) {
		$i++;
    $rightA = '';
		$editVal = (($a->typ == AT_EDIT) ? $a->odpovedDecimal : '');
		if (getSetState() == SADA_READ_ONLY) {
			$cssClass = 'class="';

			if ($a->spravna) {
				$cssClass .= 'right';
        $rightA = 'Správná: ';
      	$rightA .= $a->data2 + 0;
			}
			elseif (!$a->spravna && $a->selected)
				$cssClass .= 'wrong';

			if ($a->typ == AT_EDIT) {
				$cssEdit = ' ';
				if ($a->selected)
					if ($a->data2 == $a->odpovedDecimal)
						$cssEdit .= 'right';
					else
						$cssEdit .= 'wrong';
				elseif ($a->spravna) {
					$editVal = $a->data2 + 0;
					$cssEdit .= 'shouldbe';
				}
			}

		$cssClass .= '"';
		}
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
				$x = '<span>'.$a->data.'</span><input class="decimalTextBox'.$cssEdit.'" type="text" name="edit-'.$i.'" value="'.$editVal.'" '.$readOnly.' autocomplete="off" />'.$rightA;
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
			$out .= PHP_EOL.'  <li '.$cssClass.'><input type="checkbox" name="'.$i.'" '.$readOnly.' '.$checked.'/>'.$x.'</li>';
		else
			$out .= PHP_EOL.'  <li '.$cssClass.'><input type="radio" name="moznost" value="'.$i.'" '.$readOnly.' '.$checked.'/>'.$x.'</li>';
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
	return spockSay("Zde je otázka ".(getPosition()+1)." z ".getQCount().($comment?'<br />'.$comment:''));
}

function renderSetParams(){
	$out = '<div id="temata"><ul>';

	foreach (getTemas() as $tema) {
		$out .= '<li><input type="checkbox" name="tema-'.$tema->id.'" /><span class="name">'.htmlspecialchars($tema->jmeno).'</span><div class="description">'.htmlspecialchars($tema->komentar).'</div></li>';
	}
  $out .= '</ul></div>';
	//zohlednit defaultSetParams
	$out .= '<div id="limit"><span>Velikost sady</span><input type="number" name="limit" min="1" max="30" value="10" step="1" /></div>';

	return '<div id="setparams">'.$out.'</div><hr class="clearfix" />';
}
