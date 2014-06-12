<?php

define('MSG_DBG', 0);
define('MSG_INFO', 1);
define('MSG_WARNING', 2);
define('MSG_ERROR', 3);
$_msgClasses = array(
  0 => 'debug',
  1 => 'info',
  2 => 'warning',
  3 => 'error');


function flm($msg, $caption = '', $priority = MSG_DBG) {
  if (is_object($msg) || is_array($msg)) {
    $msg = '<pre>'.print_r($msg, 1).'</pre>';
  }
  
  $obj = new stdClass();
  $obj->caption = $caption;
  $obj->text = $msg;
  $obj->pri = $priority;

  $_SESSION['flm'][] = $obj;
}

function renderFlashMsg() {
  global $_msgClasses;
  
  if (!isset($_SESSION['flm']) || !is_array($_SESSION['flm']))
    return '';

  $output = '';

  foreach ($_SESSION['flm'] as $msg)
    $output .= '<div class="'.$_msgClasses[$msg->pri].' stored">'.($msg->caption?($msg->caption."<br />\n"):'').$msg->text.'</div>';

  unset($_SESSION['flm']);

//$_SESSION['flm-history'][] = $output;

  return '<div id="flash-msg">'.PHP_EOL.$output.PHP_EOL.'</div>';
}