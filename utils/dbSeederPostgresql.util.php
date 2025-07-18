<?php
// Set up requirements
declare(strict_types=1);

require 'bootstrap.php';
require 'vendor/autoload.php';
require_once UTILS_PATH . '/envSetter.util.php';

$host     = $pgConfig['host'];
$port     = $pgConfig['port'];
$username = $pgConfig['user'];
$password = $pgConfig['pass'];
$dbname   = $pgConfig['db'];

$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// gets CLI argument if available (ex. `composer postgresql:seed -- users`)
$table = $argv[1] ?? 'all';

// Function to seed users
function seedUsers($pdo) {
    echo "Seeding users…\n";
    $data = require DUMMIES_PATH . '/users.staticData.php';
    $stmt = $pdo->prepare("
        INSERT INTO users (username, role, first_name, middle_name, last_name, password)
        VALUES (:username, :role, :fn, :mn, :ln, :pw)
    ");
    foreach ($data as $u) {
        $stmt->execute([
            ':username' => $u['username'],
            ':role'     => $u['role'],
            ':fn'       => $u['first_name'],
            ':mn'       => $u['middle_name'] ?? null,
            ':ln'       => $u['last_name'],
            ':pw'       => password_hash($u['password'], PASSWORD_DEFAULT),
        ]);
    }
    echo "Users seeded.\n";
}

// Function to seed meetings
function seedMeetings($pdo) {
    echo "Seeding meetings…\n";
    $data = require DUMMIES_PATH . '/meetings.staticData.php';
    $stmt = $pdo->prepare("
        INSERT INTO meetings (title, description, scheduled_at, created_by)
        VALUES (:title, :description, :scheduled_at, (SELECT id FROM users WHERE username = :created_by_username))
    ");
    foreach ($data as $m) {
        $stmt->execute([
            ':title'                => $m['title'],
            ':description'          => $m['description'],
            ':scheduled_at'         => $m['scheduled_at'],
            ':created_by_username'  => $m['created_by_username'],
        ]);
    }
    echo "Meetings seeded.\n";
}

// Function to seed tasks
function seedTasks($pdo) {
    echo "Seeding tasks…\n";
    $data = require DUMMIES_PATH . '/tasks.staticData.php';
    $stmt = $pdo->prepare("
        INSERT INTO tasks (meeting_id, assigned_to, title, description, status, due_date)
        VALUES (
            (SELECT id FROM meetings WHERE title = :meeting_title),
            (SELECT id FROM users WHERE username = :assigned_to_username),
            :title, :description, :status, :due_date
        )
    ");
    foreach ($data as $t) {
        $stmt->execute([
            ':meeting_title'        => $t['meeting_title'],
            ':assigned_to_username' => $t['assigned_to_username'],
            ':title'                => $t['title'],
            ':description'          => $t['description'],
            ':status'               => $t['status'],
            ':due_date'             => $t['due_date'],
        ]);
    }
    echo "Tasks seeded.\n";
}

// Function to seed meeting_users
function seedMeetingUsers($pdo) {
    echo "Seeding meeting_users…\n";
    $data = require DUMMIES_PATH . '/meeting_users.staticData.php';
    $stmt = $pdo->prepare("
        INSERT INTO meeting_users (meeting_id, user_id, role)
        VALUES (
            (SELECT id FROM meetings WHERE title = :meeting_title),
            (SELECT id FROM users WHERE username = :username),
            :role
        )
    ");
    foreach ($data as $mu) {
        $stmt->execute([
            ':meeting_title' => $mu['meeting_title'],
            ':username'      => $mu['username'],
            ':role'          => $mu['role'],
        ]);
    }
    echo "Meeting users seeded.\n";
}

// Seeder dispatcher
switch ($table) {
    case 'users':
        seedUsers($pdo);
        break;

    case 'meetings':
        seedMeetings($pdo);
        break;

    case 'tasks':
        seedTasks($pdo);
        break;

    case 'meeting_users':
        seedMeetingUsers($pdo);
        break;

    case 'all':
        seedUsers($pdo);
        seedMeetings($pdo);
        seedTasks($pdo);
        seedMeetingUsers($pdo);
        break;

    default:
        echo "No seeder found for `{$table}`. Skipping.\n";
}
?>