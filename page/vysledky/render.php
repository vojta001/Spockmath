<?php

if ($jmeno === null) {
	echo jarSay('Chcete se podívat, jak některý ze studentů dopadl? Jen sem zadejte jeho jméno.', JAR_UHURA, TRUE); ?>
	<form method="post">
	<input type="text" name="jmeno" placeholder="Jméno (i prázdné)" />
	<input type="submit" value="Vybrat" />
	</form>
<?php } elseif ($jmeno !== null) {
	echo renderSadaTable($jmeno); ?>
	<form method="post">
	<input type="submit" name="reset_user" value="Zrušit" />
<?php }
