<?= $message;
?>

<?php
if ($redirectUrl != null) {
?>
    <script>
        var delay = 3000;
        var url = '<?= $redirectUrl ?>'
        setTimeout(function() {
            window.location = url;
        }, delay);
    </script>
<?
}
?>