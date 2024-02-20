<h1>Blogs overzicht</h1>
<?php if ($AuthorisedAccess) { ?>
    <h2>Mijn Blogs</h2>
    <a href=" <?= __BASEURL__; ?>/blogs/create"><button><span><i class="fa-solid fa-newspaper"></i> schrijf een nieuwe blog</span></button></a>
    <div class="dataoverview">
        <ul>
            <?php
            foreach ($myblogs as $blog) {
                echo "<li id=\"blog-" . $blog["blogId"] . "\" class=\"datarow\">
                <a href=' " . __BASEURL__ . "/blogs/read/" . $blog["blogId"] . "'><button><span>" . $blog["title"] . " - [<i>" . $blog["createdAt"] . "</i>]</span></button></a> 
                <span class=\"pointer boxtools \">
                    <i class=\"fa-solid fa-eye displaydata\"></i> <a href=' " . __BASEURL__ . "/blogs/edit/" . $blog["blogId"] . "'><i class=\"editdata fa-solid fa-pen-to-square\"></i></a> 
                </span>
                </li>";
            }
            ?>
        </ul>
    </div>

    <h2>Blogs van andere</h2>
<?php
}
?>
<div class="dataoverview">
    <ul>
        <?php
        foreach ($otherblogs as $blog) {
            echo "<li id=\"blog-" . $blog["blogId"] . "\" class=\"datarow\">
                <a href=' " . __BASEURL__ . "/blogs/read/" . $blog["blogId"] . "'><button><span>" . $blog["title"] . " - [<i>" . $blog["createdAt"] . "</i>]</span></button></a> 
                <span class=\"pointer boxtools \">
                    <a href=' " . __BASEURL__ . "/blogs/read/" . $blog["blogId"] . "'><i class=\"fa-solid fa-eye displaydata\"></i></a> 
                </span>
                </li>";
        }
        ?>
    </ul>
</div>

<script>

</script>