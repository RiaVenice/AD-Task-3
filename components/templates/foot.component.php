<?php

function footer(array $pageJs = [])
{
    ?>
    </main>

    <?php
    if (!empty($pageJs)) {
        foreach ($pageJs as $jsFile) {
            echo "<script src=\"{$jsFile}\"></script>\n";
        }
    }
    ?>

    </body>

    </html>
    <?php
}
?>