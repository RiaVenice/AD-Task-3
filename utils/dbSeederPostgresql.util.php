<?php
// Set up requirements
declare(strict_types=1);

require 'vendor/autoload.php';
require 'bootstrap.php';
require_once __DIR__ . '/envSetter.util.php';

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

// Seeder dispatcher
switch ($table) {
    case 'users':
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
        break;

    case 'project_users':
        echo "Seeding meetings…\n";
        $data = require DUMMIES_PATH . '/project_users.staticData.php';
        $stmt = $pdo->prepare("
            INSERT INTO project_users (title, description, meeting_date, location)
            VALUES (:title, :description, :meeting_date, :location)
        ");
        foreach ($data as $m) {
            $stmt->execute([
                ':title'        => $m['title'],
                ':description'  => $m['description'],
                ':meeting_date' => $m['meeting_date'],
                ':location'   => $m['location'],
            ]);
        }
        echo "Meetings seeded.\n";
        break;

    case 'tasks':
        echo "Seeding tasks…\n";
        $data = require DUMMIES_PATH . '/tasks.staticData.php';
        $stmt = $pdo->prepare("
            INSERT INTO tasks (assigned_to, users,title, description, status, due_date)
            VALUES (:assigned_to, :users, :title, :description, :status, :due_date)
        ");
        foreach ($data as $t) {
            $stmt->execute([
                ':assigned_to' => $t['assigned_to'],
                ':users' => $t['users'],
                ':title'       => $t['title'],
                ':description' => $t['description'],
                ':status'      => $t['status'],
                ':due_date'    => $t['due_date'],
            ]);
        }
        echo "Tasks seeded.\n";
        break;

    case 'meetingusers':
        echo "Seeding meeting_users…\n";
        $data = require DUMMIES_PATH . '/meetingusers.staticData.php';
        $stmt = $pdo->prepare("
            INSERT INTO meetingusers (role, status)
            VALUES (:role, :status)
        ");
        foreach ($data as $mu) {
            $stmt->execute([
                ':role'  => $mu['role'],
                ':status'     => $mu['status'],
            ]);
        }
        echo "Meeting users seeded.\n";
        break;

    case 'all':
        $argv[1] = 'users';          require __FILE__;
        $argv[1] = 'project_users';       require __FILE__;
        $argv[1] = 'tasks';          require __FILE__;
        $argv[1] = 'meetingusers';  require __FILE__;
        break;

    default:
        echo "No seeder found for `{$table}`. Skipping.\n";
}
?>