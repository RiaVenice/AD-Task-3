<?php

require_once UTILS_PATH . "/htmlEscape.util.php";

function head($pageTitle, array $pageCss = [])
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= htmlEscape($pageTitle) ?></title>

        <?php
        if (!empty($pageCss)) {
            foreach ($pageCss as $cssFile) {
                echo "<link rel=\"stylesheet\" href=\"{$cssFile}\">\n";
            }
        }
        ?>
    </head>
    <?php
}
?>