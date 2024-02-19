<h1>Mijn gegevens</h1>

<?php
if (isset($formerrors)) {
?>
    <div class="formerrors">
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
<?php

}

?>

<form action="<?= _FULLURI__; ?>" method="post">
    <ul>
        <li><label for="fullname">Naam</label><input id="fullname" name="fullname" type="text" value="<?= $fullname; ?>"></li>
        <li><label for="email">E-mail</label><input id="email" name="email" type="text" value="<?= $email; ?>"></li>
        <li><input type="submit" name="submit" value="Opslaan"></li>
    </ul>
</form>