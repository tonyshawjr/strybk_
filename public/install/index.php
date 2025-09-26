<?php
/**
 * Strybk_ Installation & Update System
 * Handles both fresh installations and database updates
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security: Create a lock file after installation to prevent unauthorized access
$lockFile = __DIR__ . '/install.lock';
if (file_exists($lockFile) && !isset($_GET['unlock'])) {
    die('
        <div style="font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
            <h2 style="color: #dc3545;">🔒 Installer Locked</h2>
            <p>For security, the installer has been locked after use.</p>
            <p>To run updates:</p>
            <ol>
                <li>Delete the file: <code>/public/install/install.lock</code></li>
                <li>Run your updates</li>
                <li>The lock file will be recreated automatically</li>
            </ol>
            <p style="color: #6c757d; font-size: 0.9em;">This prevents unauthorized access to your installation system.</p>
        </div>
    ');
}

// Check installation status
$configFile = __DIR__ . '/../app/config.php';
$isInstalled = file_exists($configFile);
$mode = $isInstalled ? 'update' : 'install';

// If installed, load config for updates
if ($isInstalled) {
    require_once $configFile;
    require_once __DIR__ . '/../app/Database.php';
}

$step = $_GET['step'] ?? 1;
$errors = [];
$success = false;

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        // Test database connection
        $_SESSION['db_config'] = [
            'host' => $_POST['db_host'],
            'port' => $_POST['db_port'] ?: 3306,
            'name' => $_POST['db_name'],
            'username' => $_POST['db_user'],
            'password' => $_POST['db_pass'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
        
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $_SESSION['db_config']['host'],
                $_SESSION['db_config']['port'],
                $_SESSION['db_config']['name']
            );
            
            $pdo = new PDO(
                $dsn,
                $_SESSION['db_config']['username'],
                $_SESSION['db_config']['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Connection successful, go to step 2
            header('Location: ?step=2');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database connection failed: ' . $e->getMessage();
        }
    } elseif ($step == 2) {
        // Create tables and admin user
        $name = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        $password = $_POST['admin_password'];
        $confirmPassword = $_POST['admin_password_confirm'];
        
        // Validate input
        if (empty($name)) $errors[] = 'Name is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters';
        if ($password !== $confirmPassword) $errors[] = 'Passwords do not match';
        
        if (empty($errors)) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                    $_SESSION['db_config']['host'],
                    $_SESSION['db_config']['port'],
                    $_SESSION['db_config']['name']
                );
                
                $pdo = new PDO(
                    $dsn,
                    $_SESSION['db_config']['username'],
                    $_SESSION['db_config']['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                // Read and execute complete schema
                $schema = file_get_contents(__DIR__ . '/schema-complete.sql');
                $statements = splitSqlStatements($schema);
                
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }
                
                // Create admin user
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare(
                    "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)"
                );
                $stmt->execute([$name, $email, $passwordHash]);
                
                // Create config file
                $configTemplate = file_get_contents(__DIR__ . '/../app/config.example.php');
                $configContent = str_replace(
                    [
                        "'host' => 'localhost'",
                        "'port' => 3306",
                        "'name' => 'your_database_name'",
                        "'username' => 'your_database_user'",
                        "'password' => 'your_database_password'",
                        "'url' => 'http://localhost:8000'",
                    ],
                    [
                        "'host' => '{$_SESSION['db_config']['host']}'",
                        "'port' => {$_SESSION['db_config']['port']}",
                        "'name' => '{$_SESSION['db_config']['name']}'",
                        "'username' => '{$_SESSION['db_config']['username']}'",
                        "'password' => '{$_SESSION['db_config']['password']}'",
                        "'url' => '" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}'",
                    ],
                    $configTemplate
                );
                
                file_put_contents($configFile, $configContent);
                
                // Clear session
                session_destroy();
                
                // Create lock file after successful installation
                file_put_contents($lockFile, date('Y-m-d H:i:s') . ' - Installation completed');
                
                $success = true;
            } catch (Exception $e) {
                $errors[] = 'Installation failed: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strybk Installation</title>
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
            color: var(--charcoal);
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: var(--purple);
            margin-bottom: 8px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 32px;
            font-size: 14px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #E0E0E0;
        }
        
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step::after {
            content: '';
            position: absolute;
            top: 12px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #E0E0E0;
        }
        
        .step:last-child::after {
            display: none;
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #E0E0E0;
            color: white;
            font-size: 12px;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }
        
        .step.active .step-number,
        .step.complete .step-number {
            background: var(--purple);
        }
        
        .step-label {
            display: block;
            margin-top: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .step.active .step-label {
            color: var(--purple);
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
        }
        
        input {
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
            background: var(--purple);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            width: 100%;
        }
        
        .btn:hover {
            background: var(--indigo);
            transform: translateY(-1px);
        }
        
        .error {
            background: #FEE;
            color: #C00;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success {
            background: #EFE;
            color: #060;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
        
        .success h2 {
            color: #060;
            margin-bottom: 12px;
        }
        
        .success a {
            display: inline-block;
            margin-top: 16px;
            padding: 12px 24px;
            background: var(--purple);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .success a:hover {
            background: var(--indigo);
        }
        
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        
        .update-notice {
            background: #F0F8FF;
            border: 2px solid var(--purple);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .update-notice h2 {
            color: var(--purple);
            margin-bottom: 12px;
        }
        
        .update-notice ul {
            margin: 16px 0;
            padding-left: 24px;
        }
        
        .update-notice li {
            margin: 8px 0;
            color: #333;
        }
    </style>
    
    <?php
    // Helper function to split SQL statements
    function splitSqlStatements($sql) {
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            $current .= $char;
            
            if (!$inString && ($char === '"' || $char === "'")) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $sql[$i-1] !== '\\') {
                $inString = false;
            } elseif (!$inString && $char === ';') {
                $statements[] = trim($current);
                $current = '';
            }
        }
        
        if (!empty(trim($current))) {
            $statements[] = trim($current);
        }
        
        return $statements;
    }
    ?>
</head>
<body>
    <div class="container">
        <h1>📚 Strybk_</h1>
        <p class="subtitle">Installation Wizard</p>
        
        <div class="step-indicator">
            <div class="step <?= $step >= 1 ? 'active' : '' ?> <?= $step > 1 ? 'complete' : '' ?>">
                <span class="step-number">1</span>
                <span class="step-label">Database</span>
            </div>
            <div class="step <?= $step >= 2 ? 'active' : '' ?> <?= $step > 2 ? 'complete' : '' ?>">
                <span class="step-number">2</span>
                <span class="step-label">Admin User</span>
            </div>
            <div class="step <?= $success ? 'active complete' : '' ?>">
                <span class="step-number">3</span>
                <span class="step-label">Complete</span>
            </div>
        </div>
        
        <?php if ($mode === 'update'): ?>
            <?php if (!isset($_GET['run'])): ?>
                <div class="update-notice">
                    <h2>Database Update Available</h2>
                    <p>Your Strybk installation needs a database update to enable new features.</p>
                    <p><strong>New features include:</strong></p>
                    <ul>
                        <li>✓ Version history for all pages</li>
                        <li>✓ Restore previous versions</li>
                        <li>✓ Track all changes with authors</li>
                    </ul>
                    <form method="GET">
                        <input type="hidden" name="run" value="update">
                        <button type="submit" class="btn">Run Database Update →</button>
                    </form>
                </div>
            <?php else:
                // Run the update
                require_once __DIR__ . '/updater.php';
                $updater = new DatabaseUpdater($config['db']);
                if ($updater->run()) {
                    // Create lock file after successful update
                    file_put_contents($lockFile, date('Y-m-d H:i:s') . ' - Update completed');
                    
                    echo '<div class="success">';
                    echo '<h2>✅ Update Complete!</h2>';
                    echo '<p>Your database has been updated successfully.</p>';
                    echo '<ul style="text-align: left; display: inline-block;">';
                    foreach ($updater->getMessages() as $message) {
                        echo '<li>' . htmlspecialchars($message) . '</li>';
                    }
                    echo '</ul>';
                    echo '<br><a href="/books">Return to Strybk →</a>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h2>Update Failed</h2>';
                    foreach ($updater->getErrors() as $error) {
                        echo '<p>' . htmlspecialchars($error) . '</p>';
                    }
                    echo '</div>';
                }
            ?>
            <?php endif; ?>
        <?php elseif (!empty($errors)): ?>
            <div class="error">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <h2>✨ Installation Complete!</h2>
                <p>Strybk has been successfully installed.</p>
                <a href="/login">Start Writing →</a>
            </div>
        <?php elseif ($step == 1): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="db_host">Database Host</label>
                    <input type="text" id="db_host" name="db_host" value="localhost" required>
                </div>
                
                <div class="form-group">
                    <label for="db_port">Database Port</label>
                    <input type="text" id="db_port" name="db_port" value="3306" placeholder="3306">
                    <p class="help-text">Leave empty for default MySQL port</p>
                </div>
                
                <div class="form-group">
                    <label for="db_name">Database Name</label>
                    <input type="text" id="db_name" name="db_name" required>
                </div>
                
                <div class="form-group">
                    <label for="db_user">Database Username</label>
                    <input type="text" id="db_user" name="db_user" required>
                </div>
                
                <div class="form-group">
                    <label for="db_pass">Database Password</label>
                    <input type="password" id="db_pass" name="db_pass">
                </div>
                
                <button type="submit" class="btn">Test Connection →</button>
            </form>
        <?php elseif ($step == 2): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="admin_name">Your Name</label>
                    <input type="text" id="admin_name" name="admin_name" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="admin_email">Email Address</label>
                    <input type="email" id="admin_email" name="admin_email" required>
                    <p class="help-text">This will be your login email</p>
                </div>
                
                <div class="form-group">
                    <label for="admin_password">Password</label>
                    <input type="password" id="admin_password" name="admin_password" minlength="8" required>
                    <p class="help-text">Minimum 8 characters</p>
                </div>
                
                <div class="form-group">
                    <label for="admin_password_confirm">Confirm Password</label>
                    <input type="password" id="admin_password_confirm" name="admin_password_confirm" minlength="8" required>
                </div>
                
                <button type="submit" class="btn">Complete Installation →</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>