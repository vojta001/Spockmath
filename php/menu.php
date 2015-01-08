<?php


function renderMenu() {
  global $page;

	$menu = array(
	  'home' => 'Testy',
	  'stats' => 'Statistiky',
	  'about' => 'O projektu'
	);

	if (1/*TODO check auth*/) {
	  $menu['edit'] = 'Editor';
	}

  $out = '';
  foreach ($menu as $key => $item)
    $out .= '<li><a href="'.WEB_ROOT.$key.'"'.($page == $key?' class="active"':'').'>'.$item.'</a></li>';

  return '<div id="menu"><ul>'.$out.'</ul></div>';
}