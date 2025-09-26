<?php
/**
 * Database Updater
 * Handles database schema updates for existing installations
 */

class DatabaseUpdater {
    private $db;
    private $errors = [];
    private $messages = [];
    
    public function __construct($config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $this->db = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            $this->errors[] = "Connection failed: " . $e->getMessage();
        }
    }
    
    public function run() {
        if (!empty($this->errors)) {
            return false;
        }
        
        $this->messages[] = "Starting database update...";
        
        // Check if page_versions table exists
        $result = $this->db->query("SHOW TABLES LIKE 'page_versions'");
        if ($result->rowCount() === 0) {
            $this->createPageVersionsTable();
        } else {
            $this->messages[] = "✓ page_versions table already exists";
        }
        
        // Check and add columns to pages table
        $this->addColumnIfNotExists('pages', 'image_path', 'VARCHAR(500) NULL');
        $this->addColumnIfNotExists('pages', 'current_version', 'INT DEFAULT 1');
        $this->addColumnIfNotExists('pages', 'version_count', 'INT DEFAULT 0');
        
        // Update app version in settings if table exists
        $this->updateAppVersion();
        
        $this->messages[] = "✅ Database update completed successfully!";
        return empty($this->errors);
    }
    
    private function createPageVersionsTable() {
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
        
        try {
            $this->db->exec($sql);
            $this->messages[] = "✓ Created page_versions table";
        } catch (PDOException $e) {
            $this->errors[] = "Failed to create page_versions table: " . $e->getMessage();
        }
    }
    
    private function addColumnIfNotExists($table, $column, $definition) {
        try {
            $result = $this->db->query("SHOW COLUMNS FROM $table LIKE '$column'");
            if ($result->rowCount() === 0) {
                // Try to add column, handle different positions gracefully
                try {
                    $this->db->exec("ALTER TABLE $table ADD COLUMN $column $definition");
                    $this->messages[] = "✓ Added column $column to $table";
                } catch (PDOException $e) {
                    // If position fails, add without position
                    if (strpos($e->getMessage(), 'AFTER') !== false) {
                        $def = preg_replace('/ AFTER .+$/', '', $definition);
                        $this->db->exec("ALTER TABLE $table ADD COLUMN $column $def");
                        $this->messages[] = "✓ Added column $column to $table";
                    } else {
                        throw $e;
                    }
                }
            } else {
                $this->messages[] = "✓ Column $column already exists in $table";
            }
        } catch (PDOException $e) {
            $this->errors[] = "Error adding column $column: " . $e->getMessage();
        }
    }
    
    private function updateAppVersion() {
        try {
            // Check if settings table exists
            $result = $this->db->query("SHOW TABLES LIKE 'settings'");
            if ($result->rowCount() > 0) {
                $stmt = $this->db->prepare("
                    INSERT INTO settings (setting_key, setting_value) 
                    VALUES ('app_version', '1.0.0')
                    ON DUPLICATE KEY UPDATE setting_value = '1.0.0'
                ");
                $stmt->execute();
                $this->messages[] = "✓ Updated app version to 1.0.0";
            }
        } catch (PDOException $e) {
            // Settings table might not exist, that's okay
            $this->messages[] = "Note: Settings table not found (optional)";
        }
    }
    
    public function getMessages() {
        return $this->messages;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}