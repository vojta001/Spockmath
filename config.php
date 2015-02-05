<?php

// DIRECTORY SETUP
define('WEB_ROOT', '/spock/');
//define('WEB_ROOT', '/'); //pro nasazení na webu


define('PHP_PATH', 'php/');
define('PAGES_PATH', 'page/');
define('IMG_PATH', WEB_ROOT.'img/');
define('CSS_PATH', WEB_ROOT.'css/');
define('JS_PATH', WEB_ROOT.'js/');

// VYCHOZI HODNOTY PRO CELOU SAJTU

// HTML HEADER DEFAULTS
$htmlTitle = 'Spockova matika';
$htmlDesc = 'Web s matematickými testy';
$htmlKwd = array('testy z matematiky', 'Spock');
$htmlAuthors = array('Vojtěch Kane Káně', 'RgB');
$favIcon = IMG_PATH.'design/favicon.png';

$admins = array('vojta001', 'houba',);

define('RELEASE_DEBUG', 1);
define('RELEASE_PRODUCTION', 2);

$release = RELEASE_DEBUG;
