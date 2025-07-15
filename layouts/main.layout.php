<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
Auth::init();
require_once TEMPLATES_PATH . '/head.component.php';
require_once TEMPLATES_PATH . '/nav.component.php';
require_once TEMPLATES_PATH . '/foot.component.php';
require_once UTILS_PATH . "/envSetter.util.php";

$user = Auth::user();

function renderMainLayout(callable $content, string $title = "App", array $customJsCss = []): void
{
    global $headNavList, $user;

    head($title, $customJsCss['css'] ?? []);
    ?>

    <body>
        <header>
            <?php navHeader($headNavList ?? [], $user ?? null); ?>
        </header>

        <main>
            <?php $content(); ?>
        </main>

        <footer>
            <?php footer($customJsCss['js'] ?? []); ?>
        </footer>
    </body>

    </html>
    <?php
}