<?php if (!isSetOpen()): ?>
<form method="post">
  Vítej pozemšťane! Co takhle malý test?
  <input type="submit" name="submit-seznam" value="Nechci, jsem lama!" />
  <input type="submit" name="submit-create" value="Dobře, že jsi vulkánec, zkusím to." />
</form>
<?php else : ?>
<form method="post">
  <?php echo "Zde je otázka ".(getPosition()+1)." z ".getQCount(); ?><br />
  <pre>
  <?php print_r(getCurrentQ()); ?>
  <?php echo getSetHash(); ?>
  </pre>
  <input type="submit" name="submit-prev" value="Předchozí" <?php if (getPosition() == 0) echo 'disabled '; ?>/>
  <?php if (getPosition() < getQCount()-1): ?>
  <input type="submit" name="submit-next" value="Další" />
  <?php else: ?>
  <input type="submit" name="submit-save" value="Uložit" />
  <?php endif; ?>
  <input type="submit" name="submit-clear" value="Smazat sadu" onclick="return confirm('Opravdu smazat?')"/>
</form>
<?php endif; ?>
