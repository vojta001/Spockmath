<?php if (!loggedIn()) {
echo (jarSay('Ha! Mám tě! Ty jsi klikl na ten klíč! Tak pozor, tohle je pouze pro učitele!', JAR_NIXON)); ?>
<p class="help">Pokud jste se sem dostali omylem, stačí si nahoře vybrat jinou stránku. Jestliže jste učitel, jste vřele vítán a stačí již jen zadat vaše údaje.</p>
<div id="login">
	<form method="post">
		<input type="text" name="usrname" placeholder="Uživatelské jméno:" />
		<input type="password" name="passwd" placeholder="Heslo:" />
		<input type="submit" name="login" value="Přihlásit" />
	</form>
</div>
<p class="help hackers">Poznámka pro hackery: Na vašem místě bych nepokoušel prezidentovou trpělivost. Stejně to nemá cenu, nefunguje ani SQL injection.</p>
<?php } else {
	echo (jarSay('Buďte zdráv, mistře! Teď už můžete do editoru.', JAR_NIXON));
	if (getPerm(getUser()) == PERM_ADMIN) {
		echo renderUsers();
		echo renderTools();
	}
}
