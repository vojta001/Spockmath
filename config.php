<?php

////////////////// WEB DIRECTORY SETUP
define('WEB_ROOT', '/spock/');
//define('WEB_ROOT', '/'); //pro nasazení na webu

define('PHP_PATH', 'php/');
define('PAGES_PATH', 'page/');
define('IMG_PATH', WEB_ROOT.'img/');
define('IMG_R_PATH', IMG_PATH.'resize1/');
define('CSS_PATH', WEB_ROOT.'css/');
define('JS_PATH', WEB_ROOT.'js/');


////////////////// FILE DIRECTORY SETUP
define('FILE_ROOT', __DIR__.'/');

define('IMG_FILEPATH', FILE_ROOT.'img/');
define('IMG_R_FILEPATH', FILE_ROOT.'img/resize1/');
define('CSS_FILEPATH', FILE_ROOT.'css/');
define('JS_FILEPATH', FILE_ROOT.'js/');





// VYCHOZI HODNOTY PRO CELOU SAJTU

// HTML HEADER DEFAULTS
$htmlTitle = 'Spockova matika';
$htmlDesc = 'Web s matematickými testy';
$htmlKwd = array('testy z matematiky', 'Spock');
$htmlAuthors = array('Vojtěch Kane Káně', 'RgB');
$favIcon = IMG_PATH.'design/favicon.png';

define('RELEASE_DEBUG', 1);
define('RELEASE_PRODUCTION', 2);

$release = RELEASE_DEBUG;
