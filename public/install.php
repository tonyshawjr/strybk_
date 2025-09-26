<?php
/**
 * Strybk_ Installer/Updater
 * Single file that handles both installation and updates
 * Place this in your public directory
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Auto-detect paths by looking for config file
$possiblePaths = [
    __DIR__ . '/../app/config.php',      // Standard structure
    __DIR__ . '/app/config.php',         // App in public
    __DIR__ . '/../../app/config.php',   // Deep structure
];

$configFile = null;
$appPath = null;

foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $configFile = $path;
        $appPath = dirname($path);
        break;
    }
}

// If no config found, try to find app directory
if (!$configFile) {
    $possibleAppPaths = [
        __DIR__ . '/../app',
        __DIR__ . '/app',
        __DIR__ . '/../../app',
    ];
    
    foreach ($possibleAppPaths as $path) {
        if (is_dir($path) && file_exists($path . '/config.example.php')) {
            $appPath = $path;
            $configFile = $path . '/config.php';
            break;
        }
    }
}

$isInstalled = $configFile && file_exists($configFile);
$mode = $isInstalled ? 'update' : 'install';

// If installed, load config for updates
if ($isInstalled) {
    require_once $configFile;
    // Database class is included via autoload in config, no need to require it separately
}

// Handle update mode
if ($mode === 'update' && isset($_GET['run'])) {
    try {
        $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset=utf8mb4";
        $db = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        $messages = [];
        $errors = [];
        
        // Check if page_versions table exists
        $result = $db->query("SHOW TABLES LIKE 'page_versions'");
        if ($result->rowCount() === 0) {
            // Create page_versions table
            $sql = "CREATE TABLE IF NOT EXISTS page_versions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                page_id INT NOT NULL,
                book_id INT NOT NULL,
                user_id INT NOT NULL,
                version_number INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                content LONGTEXT,
                word_count INT DEFAULT 0,
                kind ENUM('text', 'section', 'picture') DEFAULT 'text',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
                FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_page_versions (page_id, version_number DESC),
                INDEX idx_page_created (page_id, created_at DESC),
                UNIQUE KEY unique_page_version (page_id, version_number)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->exec($sql);
            $messages[] = "✓ Created page_versions table";
        } else {
            $messages[] = "✓ page_versions table already exists";
        }
        
        // Check and add columns to pages table
        $columns = [
            'image_path' => 'VARCHAR(500) NULL',
            'current_version' => 'INT DEFAULT 1',
            'version_count' => 'INT DEFAULT 0'
        ];
        
        foreach ($columns as $column => $definition) {
            $result = $db->query("SHOW COLUMNS FROM pages LIKE '$column'");
            if ($result->rowCount() === 0) {
                try {
                    $db->exec("ALTER TABLE pages ADD COLUMN $column $definition");
                    $messages[] = "✓ Added column $column to pages table";
                } catch (PDOException $e) {
                    $messages[] = "✓ Column $column might already exist";
                }
            } else {
                $messages[] = "✓ Column $column already exists in pages table";
            }
        }
        
        $updateSuccess = true;
        
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        $updateSuccess = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strybk <?= $mode === 'update' ? 'Update' : 'Installation' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #111;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: #111;
            margin-bottom: 8px;
            font-size: 32px;
        }
        
        h2 {
            color: #111;
            margin-bottom: 16px;
            font-size: 24px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 32px;
            font-size: 16px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            background: #111;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background: #333;
            transform: translateY(-1px);
        }
        
        ul {
            margin: 16px 0;
            padding-left: 24px;
        }
        
        li {
            margin: 8px 0;
        }
        
        .features {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .features h3 {
            margin-bottom: 12px;
            color: #111;
        }
        
        .features ul {
            margin: 0;
        }
        
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Strybk</h1>
        
        <?php if ($mode === 'update'): ?>
            <p class="subtitle">Database Update</p>
            
            <?php if (isset($updateSuccess)): ?>
                <?php if ($updateSuccess): ?>
                    <div class="success">
                        <h2>✅ Update Complete!</h2>
                        <p>Your database has been updated successfully.</p>
                        <?php if (!empty($messages)): ?>
                            <ul>
                                <?php foreach ($messages as $message): ?>
                                    <li><?= htmlspecialchars($message) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <br>
                        <a href="/books" class="btn">Go to Library →</a>
                    </div>
                <?php else: ?>
                    <div class="error">
                        <h2>❌ Update Failed</h2>
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="info">
                    <p>Your Strybk installation needs a database update to enable new features.</p>
                </div>
                
                <div class="features">
                    <h3>New Features:</h3>
                    <ul>
                        <li>📝 Complete version history for all pages</li>
                        <li>↩️ Restore any previous version with one click</li>
                        <li>👥 Track all changes with author information</li>
                        <li>📊 Version statistics and comparison</li>
                    </ul>
                </div>
                
                <form method="GET">
                    <input type="hidden" name="run" value="1">
                    <button type="submit" class="btn">Run Database Update →</button>
                </form>
            <?php endif; ?>
            
        <?php else: ?>
            <p class="subtitle">Installation Required</p>
            
            <div class="error">
                <p>No configuration file found. Please ensure:</p>
                <ul>
                    <li>You have uploaded all files correctly</li>
                    <li>The <code>app/config.php</code> file exists</li>
                    <li>File permissions are set correctly</li>
                </ul>
                <p>If this is a fresh installation, please use the full installer package.</p>
            </div>
        <?php endif; ?>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #e0e0e0;">
        
        <p style="font-size: 12px; color: #999; text-align: center;">
            Strybk Version 1.0.0 | 
            <?php if ($configFile): ?>
                Config: <code><?= basename(dirname($configFile)) ?>/config.php</code>
            <?php else: ?>
                Config: Not found
            <?php endif; ?>
        </p>
    </div>
</body>
</html>