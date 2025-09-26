<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Strybk') ?> - Strybk</title>
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="/css/app.css">
    
    <!-- Component-specific styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #FFFFFF;
            color: #111111;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Main container for all content */
        .page-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Simple header */
        .header {
            padding: 40px 0;
            margin-bottom: 40px;
        }
        
        .header-content {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #111111;
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        
        .logo:hover {
            opacity: 0.8;
        }
        
        .logout-button {
            position: absolute;
            right: 0;
            background: none;
            border: 1px solid #E5E5E5;
            padding: 8px 16px;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #666666;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .logout-button:hover {
            border-color: #111111;
            color: #111111;
        }
        
        .logout-form {
            display: inline;
        }
        
        .back-button {
            position: absolute;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: background 0.2s ease;
            color: #111111;
        }
        
        .back-button:hover {
            background: #F5F5F5;
        }
        
        /* Container override */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Flash messages */
        .flash-container {
            max-width: 1280px;
            margin: 0 auto 24px;
            padding: 0 24px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #F5F5F5;
            color: #111111;
            border: 1px solid #E5E5E5;
        }
        
        .alert-error {
            background: #111111;
            color: white;
            border: 1px solid #111111;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .page-container {
                padding: 0 16px;
            }
            
            .header {
                padding: 24px 0;
                margin-bottom: 24px;
            }
            
            .logo {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <?php if ($auth->check()): ?>
    <div class="page-container">
        <header class="header">
            <div class="header-content">
                <?php if (isset($showBackButton) && $showBackButton): ?>
                    <a href="/books" class="back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                <?php endif; ?>
                <a href="/books" class="logo">strybk</a>
                <form method="POST" action="/logout" class="logout-form">
                    <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
        </header>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-container">
            <?php foreach ($_SESSION['flash'] as $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>