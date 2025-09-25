<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Strybk') ?> - Strybk</title>
    <style>
        :root {
            --purple: #6C4AB6;
            --indigo: #2E1A47;
            --lime: #A8FF60;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--purple);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }
        
        .navbar-nav a {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .navbar-nav a:hover {
            color: var(--purple);
        }
        
        .navbar-nav .btn {
            padding: 0.5rem 1rem;
            background: var(--purple);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        
        .navbar-nav .btn:hover {
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: var(--purple);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 74, 182, 0.3);
        }
        
        .btn-primary {
            background: var(--purple);
            color: white;
        }
        
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .icon {
            display: inline-block;
            vertical-align: middle;
        }
        
        h1 {
            font-size: 2rem;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }
        
        h2 {
            font-size: 1.5rem;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
        }
        
        .flash-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            background: var(--gray-100);
            border-left: 4px solid var(--gray-400);
        }
        
        .flash-success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        
        .flash-error {
            background: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
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
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/books">Books</a></li>
                <li>
                    <span style="color: var(--gray-500);">
                        <?= htmlspecialchars($auth->user()['name'] ?? $auth->user()['email']) ?>
                    </span>
                </li>
                <li>
                    <form method="POST" action="/logout" style="display: inline;">
                        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                        <button type="submit" class="btn btn-sm" style="background: var(--gray-600);">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $flash): ?>
            <div class="container">
                <div class="flash-message flash-<?= $flash['type'] ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>