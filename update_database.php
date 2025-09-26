<?php
/**
 * One-time database update script
 * DELETE THIS FILE AFTER RUNNING!
 */

require_once 'app/config.php';

try {
    $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset=utf8mb4";
    $db = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<h2>Strybk Database Update</h2>";
    echo "<pre>";
    
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
        echo "✓ Created page_versions table\n";
    } else {
        echo "✓ page_versions table already exists\n";
    }
    
    // Check and add columns to pages table
    $columns = [
        'image_path' => 'VARCHAR(500) NULL AFTER word_count',
        'current_version' => 'INT DEFAULT 1 AFTER word_count',
        'version_count' => 'INT DEFAULT 0 AFTER current_version'
    ];
    
    foreach ($columns as $column => $definition) {
        $result = $db->query("SHOW COLUMNS FROM pages LIKE '$column'");
        if ($result->rowCount() === 0) {
            // Try to add after word_count, if that fails, just add it
            try {
                $db->exec("ALTER TABLE pages ADD COLUMN $column $definition");
                echo "✓ Added column $column to pages table\n";
            } catch (Exception $e) {
                // Try without AFTER clause
                $def = explode(' AFTER ', $definition)[0];
                $db->exec("ALTER TABLE pages ADD COLUMN $column $def");
                echo "✓ Added column $column to pages table\n";
            }
        } else {
            echo "✓ Column $column already exists in pages table\n";
        }
    }
    
    echo "\n<strong style='color: green;'>✅ Database update completed successfully!</strong>\n";
    echo "\n<strong style='color: red;'>⚠️ DELETE THIS FILE (update_database.php) NOW FOR SECURITY!</strong>";
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<pre>";
    echo "<strong style='color: red;'>❌ Error: " . $e->getMessage() . "</strong>";
    echo "</pre>";
}
?>