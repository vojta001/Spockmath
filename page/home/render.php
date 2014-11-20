<?php /*flm($_SESSION);*/ if (getSetState() == HOME_INIT) { ?>
<?php echo spockSay('Vítej pozemšťane! Co takhle malý test?', FALSE); ?>
<form method="post">
	<input type="submit" name="submit-seznam" value="Nechci, jsem lama!" />
	<input type="submit" name="submit-start" value="Dobře, že jsi vulkánec, zkusím to." />
</form>
<?php } elseif (getSetState() == HOME_TEMA) { ?>
<?php echo spockSay('Vyber si téma!', TRUE); ?>
<form method="post">
	<?php echo renderSetParams(); ?>
	<input type="submit" name="submit-seznam" value="Rozmyslel jsem si to!" />
	<input type="submit" name="submit-create" value="Hurááá na to!" />
</form>
<?php } elseif (in_array(getSetState(), array(SADA_OPEN, SADA_READ_ONLY))) { ?>
<form method="post">
	<?php echo renderSpockQuestion(); ?>
	<?php echo renderQ(); ?>

	<?php if (getSetState() == SADA_READ_ONLY) { ?>
	<input type="text" name="name" placeholder="Zadej svoje jméno:">
	<?php } ?>
	<input type="submit" name="submit-prev<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="Předchozí" <?php if (getPosition() == 0) echo 'disabled '; ?>/>

	<?php if (getPosition() < getQCount()-1) { ?>
	<input type="submit" name="submit-next<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="Další" />
	<?php } else { ?>
	<input type="submit" name="submit-save<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="<?php if (getSetState() == SADA_READ_ONLY) echo ('Konec'); else echo ('Uložit'); ?>" />
	<?php } ?>
	<input type="submit" name="submit-clear" value="Smazat sadu" onclick="return confirm('Opravdu smazat?')"/>
</form>
<?php } ?>