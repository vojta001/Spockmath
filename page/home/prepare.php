<?php

require PHP_PATH.'spockmath.php';

$jsScripts[] = '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
$jsScripts[] = JS_PATH.'mathquill.min.js';
$cssStyles[] = CSS_PATH.'mathquill.css';


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
      $x = '<img src="'.PATH_IMG.$q->data.'" alt="otázka" />';
      break;
    case QT_MATH:                 
      $x = '<p class="mathquill-embedded-latex">'.$q->data.'</p>';
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
    		$x = '<img src="'.PATH_IMG.$a->data.'" alt="otázka" />';
      	break;
    	case AT_MATH:                 
    	  $x = '<span class="mathquill-embedded-latex">'.$a->data.'</span>';
      	break;
    	case AT_EDIT:                 
    	  $x = '<input type="text" name="edit-'.$i.'" value="" />';
      	break;
      default:
        flm('Není definováno chování pro odpověď typu '.$a->typ, '', MSG_ERROR);
        $x = '<p class="error">Zobrazení není definováno!</p>';
        break;
		}
		$out .= '<li><input type="'.($q->multi?'checkbox':'radio').'" name="moznost" value="'.$i.'" />'.$x.'</li>';	
	}
	
  return '<div class="odpoved"><ul>'.$out.'</ul></div>';	 
} 


function renderQ() {
	$q = getCurrentQ();   
	$out = renderotazka($q);
	$out .= renderOdpoved($q);
	return '<div class="priklad">'.$out.'</div>';
}               