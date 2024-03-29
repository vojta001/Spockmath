<?php if (getSetState() == HOME_INIT) { ?>
<?php echo jarSay('Vítej pozemšťane! Co takhle malý test?', JAR_SPOCK, false); ?>
<form method="post">
	<input type="submit" name="submit-seznam" value="Nechci, jsem lama!" />
	<input type="submit" name="submit-start" value="Dobře, že jsi vulkánec, zkusím to." />
</form>
<?php } elseif (getSetState() == HOME_TEMA) { ?>
<?php echo jarSay('Vyber si téma!', JAR_SPOCK, true); ?>
<form method="post">
	<?php echo renderSetParams(); ?>
	<input type="submit" name="submit-seznam" value="Rozmyslel jsem si to!" />
	<input type="submit" name="submit-create" value="Hurááá na to!" />
</form>
<?php } elseif (getSetState() == SADA_OPEN) { ?>
<form method="post">
	<?php echo renderSpockQuestion(); ?>
	<?php echo renderQ(); ?>

	<input type="submit" name="submit-prev" value="Předchozí" <?php if (getPosition() == 0) echo 'disabled="disabled" '; ?>/>

	<?php if (getPosition() < getQCount()-1) { ?>
	<input type="submit" name="submit-next" value="Další" />
	<?php } else { ?>
	<input type="submit" name="submit-save" value="Uložit" />
	<?php } ?>
	<input type="submit" name="submit-clear" value="Smazat sadu" onclick="return confirm('Opravdu smazat?')"/>
</form>
<?php } elseif (getSetState() == SADA_REG) { ?>
<form method="post">
	<?php echo jarSay('Pověz nám něco o sobě a můžeš si prohlédnout své skóre!', JAR_SPOCK); ?>
	<input type="text" name="name" placeholder="Zadej svoje jméno:" />
	<input type="submit" name="submit-reg" value="Dokončit a vyhodnotit" />
</form>
<?php } elseif (getSetState() == SADA_READ_ONLY) { ?>
<form method="post">
	<?php echo renderSpockQuestion(); ?>
	<?php echo renderQ(); ?>

	<input type="submit" name="submit-prev-ro" value="Předchozí" <?php if (getPosition() == 0) echo 'disabled="disabled" '; ?>/>
	<input type="submit" name="submit-score" value="Přehled skóre" />
	<input type="submit" name="submit-next-ro" value="Další" <?php if (getPosition() >= getQCount() -1) echo 'disabled="disabled" '; ?>/>
</form>
<?php } elseif (getSetState() == SADA_SCORE) { ?>
<form method="post">
	<?php echo jarSay('No tak takhle jsi dopadl:', JAR_SPOCK, true); ?>
	<?php echo renderScore(); ?>
	<input type="submit" name="submit-walk" value="Projít sadu" />
	<input type="submit" name="submit-end" value="Konec" />
</form>
<?php } ?>
