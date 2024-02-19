        <script type="text/javascript" src="<?= __BASEURL__ ?>/js/jquery.richtext.js"></script>
        <link rel="stylesheet" href="<?= __BASEURL__ ?>/js/richtext.min.css">

        <?php
        if ($blog_owner) {
            echo "<a href=' " . __BASEURL__ . "/blogs/edit/" . $blog_blogId . "'><button><i class=\"editdata fa-solid fa-pen-to-square\"></i></button></a>";
        }
        ?>
        <h1>Blog: <?= $blog_title; ?> </h1>
        <p>blog gemaakt op <?= $blog_createdAt; ?></p>
        <p>blog geschreven door <?= $blog_author; ?></p>
        <br />


        <?php

        if ($editblog == true) {
        ?>

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
                    echo '<div class="formsuccessful"><i class="fa-solid fa-circle-check"></i>Wijziginen zijn opgeslagen.</div>';
                }
            }

            ?>


            <form action="<?= _FULLURI__; ?>" method="post">
                <input name="blogfile" type="hidden" value="<?= $blog_file; ?>" readonly>
                <ul>
                    <li><label for="blogtitle">Blog titel</label><input id="blogtitle" name="blogtitle" type="text" value="<?= $blog_title; ?>"></li>
                    <li>
                        <label>Inhoud</label>
                        <textarea class="content" name="blogcontent">
        <?
        }
        ?>
        <?= $blog_content; ?>
        <?php

        if ($editblog == true) {

        ?>
            </textarea>
                    </li>
                    <li><input type="submit" name="submit" value="Opslaan"></li>
                </ul>
            </form>
            </br>
            <script>
                $(document).ready(function() {
                    $('.content').richText();
                });
            </script>
        <?
        }
        ?>