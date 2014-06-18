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

