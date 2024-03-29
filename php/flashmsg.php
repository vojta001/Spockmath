<?php

$cssStyles[] = "flashmsg.css";

define('MSG_DBG', 0);
define('MSG_INFO', 1);
define('MSG_WARNING', 2);
define('MSG_ERROR', 3);
$_msgClasses = array(
	0 => 'debug',
	1 => 'info',
	2 => 'warning',
	3 => 'error'
);


function flm($msg, $caption = '', $priority = MSG_DBG) {
	global $release;

	$typeMsg = '';
	if ($priority == MSG_DBG && $release == RELEASE_DEBUG) {
		$typeMsg = '<p style="font-size: xx-small;">Type is '.gettype($msg).'</p>'.PHP_EOL;
	}

	if (is_object($msg) || is_array($msg)) {
		$msg = '<pre>'.print_r($msg, 1).'</pre>';
	}

	$msg .= $typeMsg;

	if ($release == RELEASE_DEBUG) {
		$calledFrom = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		$msg .= '<p style="font-size: xx-small;">Called from function '.$calledFrom[1]['function'].' in<br />'.$calledFrom[0]['file'].':'.$calledFrom[0]['line'].'</p>';
	}

	$obj = new stdClass();
	$obj->caption = $caption;
	$obj->text = $msg;
	$obj->priority = $priority;

	$_SESSION['flm'][] = $obj;
}

function renderFlashMsg() {
	global $_msgClasses;
	global $release;

	if (!isset($_SESSION['flm']) || !is_array($_SESSION['flm']))
		return '';

	$output = '';

	foreach ($_SESSION['flm'] as $msg)
		if ($release == RELEASE_DEBUG || $msg->priority != MSG_DBG)
			$output .= PHP_EOL.'	<div class="'.$_msgClasses[$msg->priority].'">'.($msg->caption?($msg->caption.'<br />'):'').$msg->text.'</div>';

	unset($_SESSION['flm']);

	//$_SESSION['flm-history'][] = $output;

	return '<div id="flash-msg">'.$output.PHP_EOL.'</div>'.PHP_EOL;
}