<?php
require_once PHP_PATH.'spockmath.php';

if (!loggedIn())
	redirect404();

function renderSadaTable($jmeno) {
	$sets = getAnsweredSets($jmeno);
	$out = "<table>\n<tr><td>Čas</td><td>Jméno</td></tr>\n";
	foreach($sets as $set) {
		$out .= '<tr>';
		$out .= '<td>'.$set->datum.'</td>';
		$name = $set->jmeno?$set->jmeno:'&lt;bez jména&gt;';
		$out .= '<td><a href="'.WEB_ROOT.'vysledky/'.$set->id.'">'.$name.'</a></td>';
		$out .= '</tr>'.PHP_EOL;
	}
	$out .= '</table>'.PHP_EOL;
	return $out;
}
