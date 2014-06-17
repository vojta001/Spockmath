<?php

require PHP_PATH.'spockmath.php';

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



if (isSetOpen()) {


}
else {


}

