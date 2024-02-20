<h1>Gebruikers</h1>

<div class="dataoverview">
    <ul>
        <?php
        foreach ($users as $user) {
            echo "<li id=\"user-" . $user["id"] . "\" class=\"datarow\">
                <span>" . $user["name"] . " [" . $user["username"] . "]</span> 
                <span class=\"pointer boxtools \">
                    <i class=\"fa-solid fa-eye displaydata\"></i> 
                    <a href='./gebruikers/edit/" . $user["id"] . "'><i class=\"editdata fa-solid fa-pen-to-square\"></i></a>
                </span>
                <div class=\"toggle\" >   
            
                <ul class=\"databox\">
                <li><label>Naam</label><input name='fullname' value='" . $user["name"] . "' readonly></li>
                <li><label>E-mail</label><input name='fullname' value='" . $user["email"] . "' readonly></li>
                <li><label>gebruikersnaam</label><input name='fullname' value='" . $user["username"] . "' readonly></li>
                <li><label>role</label><input name='fullname' value='" . $user["RolesName"] . "' readonly></li>
                </ul>
                </div>
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