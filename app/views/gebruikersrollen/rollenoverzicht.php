<h1>Gebruikersrollen</h1>

<div class="dataoverview">
    <ul>
        <?php
        foreach ($roles as $rol) {
            echo "<li id=\"rol-" . $rol["RolesId"] . "\" class=\"datarow\">
                <span>" . $rol["RolesName"] . "</span> 
                <span class=\"pointer boxtools \">
                    <i class=\"fa-solid fa-eye displaydata\"></i> ";

            if ($rol["RolesId"] != 1) {
                echo "
                    <a href=' " . __BASEURL__ . "/gebruikersrollen/edit/" . $rol["RolesId"] . "'><i class=\"editdata fa-solid fa-pen-to-square\"></i></a>
                    ";
            }
            echo "
                </span>
                <div class=\"toggle\" >   
                ";
            if (count($rol["rules"]) > 0) {
                echo "          
                <ul class=\"databox\">
                ";

                foreach ($rol["rules"] as $rul) {
                    echo "<li>" . $rul["rulename"] . ": ";
                    switch ($rul["rulelevel"]) {
                        case "1":
                            echo "lezen";
                            break;
                        case "2":
                            echo "lezen & bewerken";
                            break;
                        case "3":
                            echo "lezen, bewerken & verwijderen";
                            break;
                    }

                    echo "</li>";
                }
                echo "
                </ul>";
            } else {
                echo "Voor deze rol zijn nog geen rechten ingesteld.";
            }
            echo " </div>
                </li>";
        }
        ?>
    </ul>
</div>

<script>
    $(".datarow").on("click", function() {
        if ($(".toggle", this).is(":hidden")) {
            $(".toggle", this).toggle("slow", function() {});
            $(".displaydata", this).removeClass("fa-eye");
            $(".displaydata", this).addClass("fa-eye-slash");
        }
    });

    $(".displaydata").on("click", function() {
        var self = $(this).parent().parent();
        $(this).removeClass("fa-eye-slash");
        $(this).addClass("fa-eye");

        $(".toggle", self).hide("slow", function() {});
    });

    $(".editdata").on("click", function() {
        var self = $(this).parent().parent().attr('id');
        var ret = self.replace('user-', '');

        $.post("ajax/test.html", function(data) {
            $(".result").html(data);
        });
        alert(ret);
    });
</script>