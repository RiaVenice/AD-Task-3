<?php
declare(strict_types=1);
require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';
require_once UTILS_PATH . '/auth.util.php';
require_once UTILS_PATH . '/envSetter.util.php';

Auth::init();

$host     = $pgConfig['host'];
$port     = $pgConfig['port'];
$username = $pgConfig['user'];
$password = $pgConfig['pass'];
$dbname   = $pgConfig['db'];

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$action = $_POST['action'] ?? $_GET['action'] ?? null;

// --- LOGIN ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');

    if (Auth::login($pdo, $usernameInput, $passwordInput)) {
        $user = Auth::user();

        if ($user["role"] === "team lead") {
            header('Location: /pages/users/index.php');
        } else {
            header('Location: /index.php');
        }
        exit;
    } else {
        header('Location: /pages/login/index.php?error=Invalid%Credentials');
        exit;
    }
}

// --- SIGNUP ---
elseif ($action === 'signup' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = trim($_POST['username'] ?? '');
    $firstName    = trim($_POST['firstname'] ?? '');
    $middleName   = trim($_POST['middlename'] ?? '');
    $lastName     = trim($_POST['lastname'] ?? '');
    $password     = trim($_POST['password'] ?? '');
    $confirm      = trim($_POST['confirm_password'] ?? '');
    $role         = trim($_POST['role'] ?? '');

    // Validate required fields
    if (empty($username) || empty($firstName) || empty($lastName) || empty($password) || empty($role)) {
        header("Location: /pages/signup/index.php?error=Please%fill%in%all%required%fields");
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm) {
        header("Location: /pages/signup/index.php?error=Passwords%do%not%match");
        exit;
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->fetch()) {
        header("Location: /pages/signup/index.php?error=Username%already%exists");
        exit;
    }

    // Insert user into DB
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, middle_name, last_name, password, username, role)
        VALUES (:first_name, :middle_name, :last_name, :password, :username, :role)
    ");

    $stmt->execute([
        'first_name'  => $firstName,
        'middle_name' => $middleName,
        'last_name'   => $lastName,
        'password'    => $hashedPassword,
        'username'    => $username,
        'role'        => $role
    ]);

    header("Location: /pages/login/index.php?message=Account%created%successfully");
    exit;
}

// --- LOGOUT ---
elseif ($action === 'logout') {
    Auth::logout();
    header('Location: /pages/login/index.php');
    exit;
}

// --- INVALID ACTION ---
header('Location: /pages/login/index.php');
exit;
