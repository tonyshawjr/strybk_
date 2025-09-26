<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($title) ?> - Strybk</title>
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="/css/app.css">
    
    <!-- Page-specific styles -->
    <style>
        body {
            background: linear-gradient(135deg, #111111 0%, #333333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-5);
        }
        
        .login-container {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-2xl);
            max-width: 400px;
            width: 100%;
            padding: var(--space-10);
            animation: slideIn var(--transition-slow);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: var(--space-8);
        }
        
        .login-logo h1 {
            color: #111111;
            font-size: var(--text-4xl);
            margin-bottom: var(--space-2);
        }
        
        .login-logo p {
            color: var(--gray-600);
            font-size: var(--text-sm);
        }
        
        .remember-label {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            font-size: var(--text-sm);
            color: var(--gray-600);
        }
        
        .demo-info {
            margin-top: var(--space-8);
            padding: var(--space-4);
            background: var(--gray-50);
            border-radius: var(--radius-md);
            font-size: var(--text-sm);
            color: var(--gray-700);
        }
        
        .demo-info strong {
            font-weight: 600;
            color: var(--gray-900);
        }
        
        /* Override button for full width on login */
        .login-container .btn-primary {
            width: 100%;
        }
        
        .back-link {
            text-align: center;
            margin-top: var(--space-6);
            font-size: var(--text-sm);
        }
        
        .back-link a {
            color: #111111;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <h1>📚 Strybk_</h1>
            <p>Write beautiful books online</p>
        </div>
        
        <?php if ($flash = get_flash()): ?>
            <div class="alert alert-<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input"
                    value="<?= escape(old('email')) ?>"
                    required 
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input"
                    required
                >
            </div>
            
            <div class="form-group">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    <span>Remember me</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
        
        <div class="demo-info">
            <strong>Demo Account:</strong><br>
            Email: demo@strybk.com<br>
            Password: demo123
        </div>
        
        <div class="back-link">
            <a href="/">← Back to home</a>
        </div>
    </div>
</body>
</html>