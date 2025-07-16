<?php
declare(strict_types=1);
require_once UTILS_PATH . "/htmlEscape.util.php";

function navHeader(array $navItems, ?array $user): void
{
    ?>
    <style>
        header {
            background-color: #fff;
            padding: 1rem 2rem;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-left a {
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            color: #34699A;
        }

        .nav-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-right a {
            font-size: 1rem;
            text-decoration: none;
            color: #333;
            font-weight: 700;
            transition: color 0.3s;
        }

        .nav-right a:hover {
            color: #34699A;
        }

        .nav-right span {
            font-weight: 500;
            color: #222;
        }
    </style>

    <header>
        <nav class="navbar">
            <div class="nav-left">
                <a href="../index.php">Meeting Calendar</a>
            </div>
            <div class="nav-right">
                <?php if ($user): ?>
                    <span>Welcome, <?= htmlspecialchars($user['first_name']) ?></span>
                    <a href="/handlers/auth.handler.php?action=logout">Logout</a>
                <?php else: ?>
                    <a href="/pages/login/index.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <?php
}