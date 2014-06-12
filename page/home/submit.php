<?php

require PHP_PATH.'spockmath.php';

flm($_POST, '$_POST caught in submit: Nanana dlouhej text, neser...');

if (isset($_POST['submit-seznam'])) {
  header("Location: http://seznam.cz");
  exit;
}
elseif (isset($_POST['submit-create'])) {
  prepareSet(3);
}
elseif (isset($_POST['submit-next'])) {
  $_SESSION['sada']['pozice']++;
}
elseif (isset($_POST['submit-prev'])) {
  $_SESSION['sada']['pozice']--;
}
elseif (isset($_POST['submit-clear'])) {
  unset($_SESSION['sada']);
}
elseif (isset($_POST['submit-save'])) {
  flm('Sorry, barde, tohle ještě neděláme...', '', MSG_INFO);
}


?>
