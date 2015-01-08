<?php
if (isset($_GET['name']) && $_GET['name'] && is_string($_GET['name'])) {
	echo(renderPersStats());
} else {
	echo(jarSay('Tak ty by jsi chtěl vedět ještě něco víc? Tady to je:', JAR_SPOCK, false));
	echo(renderStats());
	echo(jarSay('Jo tobě to ještě nestačio? Zadej mi sem jméno a podíváme se na tebe:', JAR_SPOCK, true));
	echo(renderNameEdit());
}
