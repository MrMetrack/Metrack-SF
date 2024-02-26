k<html>

<head>
    <link rel="icon" type="image/x-icon" href="/img/icon.ico">

    <meta charset="UTF-8">
    <meta name="description" content="Free Web tutorials">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metrack SF</title>
    <style>
        <?= $data["cssContent"];
        ?>
    </style>
    <script src="<?= __BASEURL__; ?>/js/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/8e4b20a05b.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="app">
        <header>
            <img src="<?= __BASEURL__; ?>/img/logo.jpeg" width="100px" height="100px">
            <span class="logotitle">
                <h1><span>de</span>Brand Rocks <span class="subtitle">SF</span></h1>
            </span>

        </header>
        <nav>
            <div>
                <ul>
                    <li><a href="<?= __BASEURL__; ?>/">Home</a></li>
                    <li><a href="<?= __BASEURL__; ?>/blogs">Blogs</a></li>
                    <?php
                    if (!isset($_SESSION["UserId"])) {
                        echo "<li><a href=\"" . __BASEURL__ . "/registreren\">Registreren</a></li>";
                    } else {
                        echo "<li><a href=\"" . __BASEURL__ . "/gebruikersrollen\">Rollen</a></li>";
                        echo "<li><a href=\"" . __BASEURL__ . "/gebruikers\">Gebruikers</a></li>";
                        echo "<li><a href=\"" . __BASEURL__ . "/MijnGegevens\">Mijn gegevens</a></li>";
                    }
                    ?>


                    <li>
                        <?=
                        (!isset($_SESSION["UserId"])) ? "<a href=\"" . __BASEURL__ . "/Login\">Login</a>" : "<a href=\"./Logout\">Logout</a>";
                        ?>
                    </li>
                </ul>
            </div>
        </nav>
        <main><?= $data["content"]; ?></main>
        <footer>footer</footer>
    </div>
</body>

</html>
