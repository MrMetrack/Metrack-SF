<h1>Registeren</h1>
<h2>Registreer je als nieuwe gebruiker.</h2>

<?php

// als er errors zijn ontstaan tijdens de field validatie dan zal $formerrors gevuld zijn met data. Onderstaande zorgt er voor dat de errors worden weergegeven.
if (isset($formerrors)) {
?>
    <div class="formerrors">

        <p><i class="fa-solid fa-circle-exclamation"></i> De onderstaande fouten zijn opgetreden.</p>

        <div class="errorlist">
            <ul>
                <?php
                foreach ($formerrors as $ferror) {
                    foreach ($ferror as $fe) {
                        echo "<li>{$fe}</li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
<?php

} else {
    if ($successful) {
        echo '<div class="formsuccessful"><i class="fa-solid fa-circle-check"></i>Je registratie is verwerkt. Ga nu naar onze inlog pagina om toegang te krijgen.</div>';
    }
}

?>

<form action="<?= _FULLURI__; ?>" method="post">
    <ul>
        <li><label for="fullname">Naam</label><input id="fullname" name="fullname" type="text" value="<?= $fullname; ?>"></li>
        <li><label for="email">E-mail</label><input id="email" name="email" type="text" value="<?= $email; ?>"></li>
        <li><label for="password">Wachtwoord</label><input id="password" name="password" type="password" value="">
        <li><label for="passwordAgain">Wachtwoord (nogmaals)</label><input id="passwordAgain" name="passwordAgain" type="password" value="">
        <li><input type="submit" name="submit" value="Registreren"></li>
    </ul>
</form>
