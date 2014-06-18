<?php

require PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit:');

if (isset($_POST['submit-seznam'])) {
  header("Location: http://seznam.cz");
  exit;
}
elseif (isset($_POST['submit-create'])) {
  prepareSet(3);
}
elseif (isset($_POST['submit-next'])) {
  setMoveNext();
}
elseif (isset($_POST['submit-prev'])) {
  setMovePrevious();
}
elseif (isset($_POST['submit-clear'])) {
  clearSet();
}
elseif (isset($_POST['submit-save'])) {
  flm('Sorry, tahle funkce ještě není implementována (bude-li vůbec).', '', MSG_ERROR);
}


?>
