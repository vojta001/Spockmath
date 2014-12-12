<?php
if (is_int($_id)) {
	echo renderQEdit($_id);
}
elseif (is_null($_id)) {
	echo jarSay('Pojďme vytvářet hodnoty pro naše studenty.<br />Zde je výčet všech otázek v databázi.<br />Můžeš přidat novou, nebo stávající upravit.', JAR_UHURA, true);
	echo renderQtab();
}
