<?php
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
Auth::init();

if (Auth::check()) {
    header("Location: /index.php");
    exit;
}

require_once LAYOUTS_PATH . "/main.layout.php";

$error = trim((string) ($_GET['error'] ?? ''));
$error = str_replace("%", " ", $error);

$message = trim((string) ($_GET['message'] ?? ''));
$message = str_replace("%", " ", $message);

$title = "Sign Up";

renderMainLayout(
    function () use ($error, $message) {
        ?>
        <section class="signup-form">
            <h2>Create an Account</h2>

            <?php if (!empty($message)): ?>
                <div style="color: green; margin-bottom: 1rem;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 1rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/handlers/auth.handler.php">
                <input type="hidden" name="action" value="signup">

                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="middlename" placeholder="Middle Name">
                <input type="text" name="lastname" placeholder="Last Name" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="text" name="role" placeholder="Role" required>

                <button type="submit">Sign Up</button>
            </form>
        </section>
        <?php
    },
    $title,
    [
        'css' => [
            "./assets/css/signup.css"
        ]
    ]
);
