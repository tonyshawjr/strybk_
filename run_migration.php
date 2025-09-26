<?php
/**
 * One-time migration script for version history
 * Delete this file after running!
 */

require_once 'app/config.php';
require_once 'app/Database.php';

try {
    $db = new Database($config['db']);
    
    // Read the migration file
    $sql = file_get_contents(__DIR__ . '/database/migrations/006_create_page_versions_table.sql');
    
    // Split into individual statements (MySQL doesn't like multiple statements in one query)
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $db->query($statement . ';');
            echo "✓ Executed: " . substr($statement, 0, 50) . "...<br>\n";
        }
    }
    
    echo "<br><strong style='color: green;'>✅ Migration completed successfully!</strong><br>\n";
    echo "<br><strong style='color: red;'>⚠️ IMPORTANT: Delete this file (run_migration.php) now for security!</strong>\n";
    
} catch (Exception $e) {
    echo "<strong style='color: red;'>❌ Error running migration: " . $e->getMessage() . "</strong>\n";
}
?>