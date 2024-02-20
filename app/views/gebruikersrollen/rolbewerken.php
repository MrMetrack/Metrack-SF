<h1>Gebruikersrol bewerken</h1>

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
        <li><label for="RolesName">Rolnaam</label><input id="RolesName" name="RolesName" type="text" value="<?= $rol["RolesName"]; ?>"></li>
    </ul>

    <h2>Rechten</h2>

    <p>Hieronder vindt u alle regels met daaronder de bevoegdheid die bij de regel hoord.</p>
    <ul>
        <?php
        foreach ($rules as $rule) {
            echo "<li><label>" . $rule["name"] . "</label>";
            echo '<select name="ruleid-' . $rule["RulesId"] . '">';
            echo '<option value="0" ';
            echo ($rule["level"] == 0) ? 'selected' : '';
            echo '>Niet ingesteld</option>';
            echo '<option value="1" ';
            echo ($rule["level"] == 1) ? 'selected' : '';
            echo '>Lezen</option>';
            echo '<option value="2" ';
            echo ($rule["level"] == 2) ? 'selected' : '';
            echo '>Lezen & bewerken</option>';
            echo '<option value="3" ';
            echo ($rule["level"] == 3) ? 'selected' : '';
            echo '>Lezen, bewerken & verwijderen </option>';
            echo '</select>';
            echo "</li>";
        }
        ?>
        <li><input type="submit" name="submit" value="Opslaan"></li>
    </ul>
</form>