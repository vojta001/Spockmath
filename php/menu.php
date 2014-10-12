<?php

$menu = array(
  'home' => 'Testy',
  'stats' => 'Statistiky',
  'about' => 'O projektu'
);

function renderMenu() {
  global $page, $menu;
  
  if (empty($menu)) return '';
  
  $out = '';
  foreach ($menu as $key => $item)
    $out .= '<li><a href="'.$key.'"'.($page == $key?' class="active"':'').'>'.$item.'</a></li>';

  return '<div id="menu"><ul>'.$out.'</ul></div>';
}