<?php
$jmeno = isset($_SESSION['vysledky']['user'])?$_SESSION['vysledky']['user']:null;
if (empty($jmeno) && empty($_GET['q'])) {
	echo jarSay('Chcete se podívat, jak některý ze studentů dopadl? Jen sem zadejte jeho jméno.', JAR_UHURA, TRUE); ?>
	<form method="post">
	<input type="text" name="jmeno" placeholder="Jméno, které student zadal při ukládání">
	<input type="submit">
	</form>
<?php } elseif (!empty($jmeno) && empty($_GET['q'])) {
	flm('zadal\'s jméno '.$jmeno);
	echo (renderSadaTable($jmeno));
} elseif (!empty($_GET['q'])) {
	unset($_SESSION['vysledky']['user']);
	prepareSetById($_GET['q']);
	header('Location: '.WEB_ROOT.'home');
	exit;
}
