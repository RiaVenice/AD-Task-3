<?php
require_once LAYOUTS_PATH . "/main.layout.php";
$mongoCheckerResult = require_once HANDLERS_PATH . "/mongodbChecker.handler.php";
$postgresqlCheckerResult = require_once HANDLERS_PATH . "/postgreChecker.handler.php";

$title = "Home Page";

renderMainLayout(
    function () use ($mongoCheckerResult, $postgresqlCheckerResult) {
        ?>
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to Meeting Calendar!</h1>
                <p>Manage your meetings and maximize your productivity.</p>
            </div>
        </section>

        <section class="db-status">
            <h2>Database Connection Status</h2>

            <p>
                MongoDB:
                <span class="<?= $mongoCheckerResult ? 'success' : 'fail' ?>">
                    <?= $mongoCheckerResult ? '✅ Connected successfully' : '❌ Failed to connect' ?>
                </span>
            </p>

            <p>
                PostgreSQL:
                <span class="<?= $postgresqlCheckerResult ? 'success' : 'fail' ?>">
                    <?= $postgresqlCheckerResult ? '✅ Connected successfully' : '❌ Failed to connect' ?>
                </span>
            </p>

        </section>
        <?php
    },
    $title,
    [
        'css' => [
            './assets/css/home.css'
        ]
    ]
);
?>