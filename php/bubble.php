<?php

$cssStyles[] = "bubble.css";

define('JAR_SPOCK', 1);
define('JAR_UHURA', 2);
define('JAR_NIXON', 3);

function jarSay($text, $jar = JAR_SPOCK, $left = FALSE) {
	$sideStr = $left?'left':'right';
	switch($jar) {
		case JAR_UHURA:
			$actorNameNominativ = 'nichelle'; 	
			$roleNameGenitiv = 'Uhury';
			break;
		case JAR_NIXON: 	
			$actorNameNominativ = 'nixon'; 	
			$roleNameGenitiv = 'Richarda Nixona';
			break;
		default:
			$actorNameNominativ = 'leonard'; 	
			$roleNameGenitiv = 'Spocka';
	}		

	$img = '<img src="'.IMG_PATH.'design/'.$actorNameNominativ.'-'.$sideStr.'.png" alt="Hlava '.$roleNameGenitiv.'" />';
	$p = '<p class="jar-bubble '.$sideStr.'">'.$text.'</p>';

	return '<div class="jar-say">'.($left?($p.$img):($img.$p)).'</div>'.PHP_EOL;	
}
