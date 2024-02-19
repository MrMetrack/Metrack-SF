        <h1>Opgeslagen</h1>

        <br />

        <p>De blog is met succes opgeslagen. Over enkele seconden wordt je automatisch doorgestuurd naar de overzicht pagina.</p>
        <p>Mocht het doorsturen langer duren dan verwacht druk dan op deze <a href="<?= __BASEURL__ ?>/blogs"> link </a>
            </br>

            <script>
                var delay = 3000;
                var url = '<?= __BASEURL__ ?>/blogs'
                setTimeout(function() {
                    window.location = url;
                }, delay);
            </script>