<?php
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
Auth::init();

if (Auth::check()) {
    header("Location: /index.php"); // Redirect logged-in users away from signup
    exit;
}
require_once LAYOUTS_PATH . "/main.layout.php";

$title = "Sign Up";

renderMainLayout(
    function () {
        ?>
        <section class="signup-form">
            <h2>Create an Account</h2>
            <form method="POST" action="/handlers/signup.handler.php">
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
