<?php
require_once '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username'];
    $firstName  = $_POST['firstname'];
    $middleName = $_POST['middlename'];
    $lastName   = $_POST['lastname'];
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $role       = $_POST['role'];

    // Basic password confirmation check
    if ($password !== $confirm) {
        die("❌ Passwords do not match.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect to PostgreSQL
        $pdo = new PDO("pgsql:host=postgresql;port=5432;dbname=database", "user", "password");

        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, middle_name, last_name, password, username, role)
            VALUES (:first_name, :middle_name, :last_name, :password, :username, :role)
        ");

        $stmt->execute([
            ':first_name'  => $firstName,
            ':middle_name' => $middleName,
            ':last_name'   => $lastName,
            ':password'    => $hashedPassword,
            ':username'    => $username,
            ':role'        => $role
        ]);

        // Redirect to login or homepage with success
        header("Location: /pages/login/index.php?registered=1");
        exit;
    } catch (PDOException $e) {
        die("❌ DB Error: " . $e->getMessage());
    }
} else {
    header("Location: /pages/signup/index.php");
    exit;
}
