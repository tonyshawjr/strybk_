<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Strybk') ?> - Strybk</title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="/css/app.css">
    
    <!-- Component-specific styles -->
    <style>
        body {
            background: var(--gray-50);
        }
        
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-base);
            padding: var(--space-4) 0;
            margin-bottom: var(--space-8);
        }
        
        .navbar-inner {
            max-width: var(--container-xl);
            margin: 0 auto;
            padding: 0 var(--space-4);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: var(--text-2xl);
            font-weight: 700;
            color: var(--purple);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }
        
        .navbar-brand:hover {
            color: var(--purple-dark);
        }
        
        .navbar-nav {
            display: flex;
            gap: var(--space-8);
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .navbar-nav li {
            margin: 0;
        }
        
        .navbar-user {
            color: var(--gray-500);
            font-size: var(--text-sm);
        }
        
        .navbar-logout {
            display: inline;
        }
        
        .navbar-logout .btn {
            background: var(--gray-600);
        }
        
        .navbar-logout .btn:hover {
            background: var(--gray-700);
        }
        
        /* Mobile Navigation */
        @media (max-width: 768px) {
            .navbar-inner {
                flex-direction: column;
                gap: var(--space-4);
            }
            
            .navbar-nav {
                width: 100%;
                justify-content: center;
                gap: var(--space-4);
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <?php if ($auth->check()): ?>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/dashboard" class="navbar-brand">
                📚 Strybk
            </a>
            <ul class="navbar-nav">
                <li><a href="/dashboard" class="nav-link">Dashboard</a></li>
                <li><a href="/books" class="nav-link">Books</a></li>
                <li>
                    <span class="navbar-user">
                        <?= htmlspecialchars($auth->user()['name'] ?? $auth->user()['email']) ?>
                    </span>
                </li>
                <li>
                    <form method="POST" action="/logout" class="navbar-logout">
                        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                        <button type="submit" class="btn btn-sm">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="container">
            <?php foreach ($_SESSION['flash'] as $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?> animate-slideIn">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>