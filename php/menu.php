<?php


function renderMenu() {
	global $page;

	$menu = array(
		'home' => 'Testy',
		'stats' => 'Statistiky',
		'about' => 'O projektu'
	);

	if (loggedIn()) {
		$menu['edit'] = 'Editor';
		$menu['vysledky'] = 'Výsledky';
	}

	$out = '';
	foreach ($menu as $key => $item)
		$out .= '<li><a href="'.WEB_ROOT.$key.'"'.($page == $key?' class="active"':'').'>'.$item.'</a></li>';

	return '<div id="menu"><ul>'.$out.'</ul></div>';
}