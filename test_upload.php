<?php
/**
 * Test script to verify upload directory structure
 * Run this on the server to check the upload paths
 */

echo "=== Upload Directory Test ===\n\n";

// Check current directory
echo "Current directory: " . getcwd() . "\n";

// Check if we're in public directory
if (basename(getcwd()) === 'public') {
    echo "✓ Currently in public directory\n";
} else {
    echo "✗ Not in public directory\n";
}

// Check for nested public/public issue
if (is_dir('public/public')) {
    echo "⚠ WARNING: Nested public/public directory detected!\n";
    echo "  This will cause 404 errors for uploads.\n";
    echo "  Files are being saved to: public/public/uploads/covers/\n";
    echo "  But URLs expect: public/uploads/covers/\n";
} else {
    echo "✓ No nested public directory issue\n";
}

// Check uploads directory
if (is_dir('uploads')) {
    echo "✓ uploads/ directory exists\n";
    
    if (is_dir('uploads/covers')) {
        echo "✓ uploads/covers/ directory exists\n";
        
        // List any existing covers
        $covers = glob('uploads/covers/*');
        if (!empty($covers)) {
            echo "\nExisting covers:\n";
            foreach ($covers as $cover) {
                echo "  - " . basename($cover) . " (size: " . filesize($cover) . " bytes)\n";
            }
        } else {
            echo "  No covers uploaded yet\n";
        }
    } else {
        echo "✗ uploads/covers/ directory missing\n";
        echo "  Creating it now...\n";
        if (mkdir('uploads/covers', 0755, true)) {
            echo "  ✓ Created uploads/covers/\n";
        }
    }
} else {
    echo "✗ uploads/ directory missing\n";
    echo "  Creating it now...\n";
    if (mkdir('uploads/covers', 0755, true)) {
        echo "  ✓ Created uploads/covers/\n";
    }
}

// Check permissions
echo "\nDirectory permissions:\n";
if (is_dir('uploads')) {
    $perms = substr(sprintf('%o', fileperms('uploads')), -4);
    echo "  uploads/: $perms\n";
}
if (is_dir('uploads/covers')) {
    $perms = substr(sprintf('%o', fileperms('uploads/covers')), -4);
    echo "  uploads/covers/: $perms\n";
}

// Test write permission
echo "\nWrite permission test:\n";
$testFile = 'uploads/covers/test_' . time() . '.txt';
if (@file_put_contents($testFile, 'test')) {
    echo "✓ Can write to uploads/covers/\n";
    unlink($testFile);
} else {
    echo "✗ Cannot write to uploads/covers/ - check permissions\n";
}

echo "\n=== Recommended Actions ===\n";
if (is_dir('public/public')) {
    echo "1. Move contents from public/public/ to public/\n";
    echo "   Run: mv public/public/* public/ && rm -rf public/public\n";
}
echo "2. Ensure uploads/covers/ has write permissions (755 or 775)\n";
echo "3. Test upload functionality from the web interface\n";