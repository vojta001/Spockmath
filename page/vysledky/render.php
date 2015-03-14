<?php
if (empty($jmeno/*post*/) && empty($q)) {
	echo (renderSadaTable('Příliš \'); DROP TABLE sada; -- žluťoučký kůň úpěl ďábelské ódy.'));
    prepareSetById(85);
	echo jarSay('Chcete se podívat, jak některý ze studentů dopadl? Jen sem zadejte jeho jméno.', JAR_UHURA, TRUE); ?>
	<form method="get">
	<input type="text" name="jmeno" placeholder="Jméno, které student zadal při ukládání">
	<input type="submit">
	</form>
<?php } elseif (!empty($jmeno) && empty($q)) {
	flm('zadal\'s jméno '.$_GET['jmeno']);
} elseif (!empty($q)) {

}
