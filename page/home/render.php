<?php if (!getSetState() == SADA_OPEN && !getSetState() == SADA_READ_ONLY): ?>
<?php echo spockSay('Vítej pozemšťane! Co takhle malý test?', FALSE); ?>
<form method="post">
	<input type="submit" name="submit-seznam" value="Nechci, jsem lama!" />
	<input type="submit" name="submit-create" value="Dobře, že jsi vulkánec, zkusím to." />
</form>
<?php else : ?>
<form method="post">
	<?php echo renderSpockQuestion(); ?>
<?php echo renderQ(); ?>
<?php if (getSetState() == SADA_READ_ONLY) echo ('<input type="text" name="name" placeholder="Zadej svoje jméno:">'); ?>
<?php //echo '<pre>'.print_r(getCurrentQ(), 1).getSetHash().</pre>; ?>
	</pre>
	<input type="submit" name="submit-prev<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="Předchozí" <?php if (getPosition() == 0) echo 'disabled '; ?>/>
<?php if (getPosition() < getQCount()-1): ?>
	<input type="submit" name="submit-next<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="Další" />
<?php else: ?>
	<input type="submit" name="submit-save<?php if (getSetState() == SADA_READ_ONLY) echo ('-ro') ?>" value="<?php if (getSetState() == SADA_READ_ONLY) echo ('Konec'); else echo ('Uložit'); ?>" />
<?php endif; ?>
	<input type="submit" name="submit-clear" value="Smazat sadu" onclick="return confirm('Opravdu smazat?')"/>
</form>
<?php endif; ?>