<h1>Login</h1>

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
        <li><label for="email">E-mail</label><input id="email" name="email" type="text" value="<?= $email; ?>"></li>
        <li><label for="password">Wachtwoord</label><input id="password" name="password" type="password" value=""></li>
        <li><input type="submit" name="submit" value="Login"></li>
    </ul>
</form>