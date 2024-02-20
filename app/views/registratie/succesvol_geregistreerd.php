<h1>Registeren</h1>
<h2>Becomes a <span class="word">Brand</span> new user</h2>

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
<p>Dankjewel voor je aanmelding. Wij hebben je met succes geregistreerd. Ga naar onze <a href="./Login">Login</a> pagina om in te loggen.</p>