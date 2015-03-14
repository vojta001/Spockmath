<?php
$jmeno = isset($_SESSION['vysledky']['user'])?$_SESSION['vysledky']['user']:null;
flm($_SESSION['vysledky'], "výsledky?");
flm(gettype($jmeno), "jméno?");
if ($jmeno === null && empty($_GET['q'])) {
	echo jarSay('Chcete se podívat, jak některý ze studentů dopadl? Jen sem zadejte jeho jméno.', JAR_UHURA, TRUE); ?>
	<form method="post">
	<input type="text" name="jmeno" placeholder="Jméno (i prázdné)" />
	<input type="submit" value="Vybrat" />
	</form>
<?php } elseif ($jmeno !== null && empty($_GET['q'])) {
	flm('zadal\'s jméno '.$jmeno);
	echo renderSadaTable($jmeno); ?>
	<form method="post">
	<input type="submit" name="reset_user" value="Zrušit" />
<?php
} elseif (!empty($_GET['q'])) {
	unset($_SESSION['vysledky']['user']);
	prepareSetById($_GET['q']);
	header('Location: '.WEB_ROOT.'home');
	exit;
}
