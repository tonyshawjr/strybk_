<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($title) ?> - Strybk</title>
    <style>
        :root {
            --purple: #6C4AB6;
            --indigo: #2E1A47;
            --lime: #A8FF60;
            --gray: #F6F6F6;
            --charcoal: #222222;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--indigo) 0%, var(--purple) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
            padding: 40px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo h1 {
            color: var(--purple);
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .logo p {
            color: #666;
            font-size: 14px;
        }
        
        .error-message {
            background: #FEE;
            color: #C00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .flash-message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .flash-success {
            background: #EFE;
            color: #060;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
            color: var(--charcoal);
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(108, 74, 182, 0.1);
        }
        
        .btn {
            width: 100%;
            background: var(--purple);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .btn:hover {
            background: var(--indigo);
            transform: translateY(-1px);
        }
        
        .forgot-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .forgot-link a {
            color: var(--purple);
            text-decoration: none;
        }
        
        .forgot-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>📚 Strybk_</h1>
            <p>Write beautiful books online</p>
        </div>
        
        <?php if ($flash = get_flash()): ?>
            <div class="flash-message flash-<?= escape($flash['type']) ?>">
                <?= escape($flash['message']) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?= escape($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= escape(old('email')) ?>"
                    required 
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
            </div>
            
            <button type="submit" class="btn">Sign In</button>
        </form>
        
        <div class="forgot-link">
            <a href="/">← Back to home</a>
        </div>
    </div>
</body>
</html>