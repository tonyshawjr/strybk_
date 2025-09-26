<?php
/**
 * Strybk Installer/Updater
 * Handles both fresh installations and updates to existing databases
 */

class DatabaseInstaller {
    private $db;
    private $errors = [];
    private $messages = [];
    
    public function __construct($config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $this->db = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
        } catch (PDOException $e) {
            $this->errors[] = "Connection failed: " . $e->getMessage();
        }
    }
    
    /**
     * Run the installation/update process
     */
    public function run() {
        if (!empty($this->errors)) {
            return false;
        }
        
        // Check if this is a fresh install or update
        if ($this->isNewInstallation()) {
            $this->messages[] = "Starting fresh installation...";
            $this->freshInstall();
        } else {
            $this->messages[] = "Existing installation detected. Checking for updates...";
            $this->updateExisting();
        }
        
        return empty($this->errors);
    }
    
    /**
     * Check if this is a new installation
     */
    private function isNewInstallation() {
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'users'");
            return $result->rowCount() === 0;
        } catch (PDOException $e) {
            return true;
        }
    }
    
    /**
     * Perform fresh installation
     */
    private function freshInstall() {
        $schemaFile = __DIR__ . '/schema-complete.sql';
        
        if (!file_exists($schemaFile)) {
            $this->errors[] = "Schema file not found: $schemaFile";
            return;
        }
        
        $sql = file_get_contents($schemaFile);
        $statements = $this->splitSqlStatements($sql);
        
        foreach ($statements as $statement) {
            if (!empty(trim($statement))) {
                try {
                    $this->db->exec($statement);
                    $this->messages[] = "✓ Executed: " . $this->getStatementType($statement);
                } catch (PDOException $e) {
                    // Ignore duplicate key errors for default data
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        $this->errors[] = "Error: " . $e->getMessage();
                    }
                }
            }
        }
        
        $this->messages[] = "Fresh installation completed!";
    }
    
    /**
     * Update existing installation
     */
    private function updateExisting() {
        // Check and add missing columns to pages table
        $this->addColumnIfNotExists('pages', 'image_path', 'VARCHAR(500) NULL AFTER word_count');
        $this->addColumnIfNotExists('pages', 'current_version', 'INT DEFAULT 1 AFTER image_path');
        $this->addColumnIfNotExists('pages', 'version_count', 'INT DEFAULT 0 AFTER current_version');
        
        // Create page_versions table if it doesn't exist
        $this->createTableIfNotExists('page_versions', "
            CREATE TABLE page_versions (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Update settings table with current version
        $this->updateSetting('app_version', '1.0.0');
        
        $this->messages[] = "Update completed!";
    }
    
    /**
     * Add column if it doesn't exist
     */
    private function addColumnIfNotExists($table, $column, $definition) {
        try {
            $result = $this->db->query("SHOW COLUMNS FROM $table LIKE '$column'");
            if ($result->rowCount() === 0) {
                $this->db->exec("ALTER TABLE $table ADD COLUMN $column $definition");
                $this->messages[] = "✓ Added column $column to $table";
            } else {
                $this->messages[] = "✓ Column $column already exists in $table";
            }
        } catch (PDOException $e) {
            $this->errors[] = "Error adding column $column: " . $e->getMessage();
        }
    }
    
    /**
     * Create table if it doesn't exist
     */
    private function createTableIfNotExists($table, $createStatement) {
        try {
            $result = $this->db->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() === 0) {
                $this->db->exec($createStatement);
                $this->messages[] = "✓ Created table $table";
            } else {
                $this->messages[] = "✓ Table $table already exists";
            }
        } catch (PDOException $e) {
            $this->errors[] = "Error creating table $table: " . $e->getMessage();
        }
    }
    
    /**
     * Update or insert setting
     */
    private function updateSetting($key, $value) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO settings (setting_key, setting_value) 
                VALUES (:key, :value)
                ON DUPLICATE KEY UPDATE setting_value = :value
            ");
            $stmt->execute(['key' => $key, 'value' => $value]);
            $this->messages[] = "✓ Updated setting: $key = $value";
        } catch (PDOException $e) {
            // Settings table might not exist in older versions
            $this->messages[] = "Note: Could not update setting $key";
        }
    }
    
    /**
     * Split SQL into individual statements
     */
    private function splitSqlStatements($sql) {
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by semicolon, but not within strings
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
    
    /**
     * Get human-readable statement type
     */
    private function getStatementType($sql) {
        $sql = strtoupper(trim($sql));
        
        if (strpos($sql, 'CREATE TABLE') === 0) {
            preg_match('/CREATE TABLE (?:IF NOT EXISTS )?([^\s(]+)/i', $sql, $matches);
            return "Created table " . ($matches[1] ?? 'unknown');
        }
        
        if (strpos($sql, 'ALTER TABLE') === 0) {
            preg_match('/ALTER TABLE ([^\s]+)/i', $sql, $matches);
            return "Modified table " . ($matches[1] ?? 'unknown');
        }
        
        if (strpos($sql, 'INSERT INTO') === 0) {
            return "Inserted default data";
        }
        
        return "Executed statement";
    }
    
    /**
     * Get results
     */
    public function getMessages() {
        return $this->messages;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

// Check if running from command line or web
if (php_sapi_name() === 'cli') {
    // Command line execution
    echo "Strybk Database Installer\n";
    echo "========================\n\n";
    
    // Load config
    require_once dirname(__DIR__) . '/app/config.php';
    
    $installer = new DatabaseInstaller($config['db']);
    if ($installer->run()) {
        echo "✅ Installation/Update successful!\n\n";
        foreach ($installer->getMessages() as $message) {
            echo $message . "\n";
        }
    } else {
        echo "❌ Installation/Update failed!\n\n";
        foreach ($installer->getErrors() as $error) {
            echo "Error: " . $error . "\n";
        }
        exit(1);
    }
} else {
    // Web execution - only allow if installer_key.php exists for security
    $keyFile = __DIR__ . '/installer_key.php';
    
    if (!file_exists($keyFile)) {
        die("Installer locked. Create install/installer_key.php to run from web.");
    }
    
    require_once dirname(__DIR__) . '/app/config.php';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Strybk Installer</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            h1 { color: #111; }
            .success { color: #10b981; }
            .error { color: #ef4444; }
            .message { padding: 5px 0; }
            .warning { background: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <h1>Strybk Database Installer</h1>
        <?php
        $installer = new DatabaseInstaller($config['db']);
        if ($installer->run()) {
            echo '<h2 class="success">✅ Installation/Update Successful!</h2>';
            foreach ($installer->getMessages() as $message) {
                echo '<div class="message">' . htmlspecialchars($message) . '</div>';
            }
            echo '<div class="warning">⚠️ <strong>IMPORTANT:</strong> Delete the install/installer_key.php file now for security!</div>';
        } else {
            echo '<h2 class="error">❌ Installation/Update Failed</h2>';
            foreach ($installer->getErrors() as $error) {
                echo '<div class="error">' . htmlspecialchars($error) . '</div>';
            }
        }
        ?>
    </body>
    </html>
    <?php
}
?>