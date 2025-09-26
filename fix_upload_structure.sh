#!/bin/bash
# Fix nested public/public directory issue on server

echo "=== Fixing Upload Directory Structure ==="
echo ""

# Navigate to the public directory
if [ -d "public" ]; then
    cd public
    echo "✓ Navigated to public directory"
else
    echo "✗ public directory not found"
    exit 1
fi

# Check for nested public directory
if [ -d "public" ]; then
    echo "⚠ Found nested public/public directory"
    echo "  Moving contents up one level..."
    
    # Move all contents from nested public to current directory
    if [ -d "public/uploads" ]; then
        echo "  Moving uploads directory..."
        mv -n public/uploads/* uploads/ 2>/dev/null || true
        rmdir public/uploads/covers 2>/dev/null || true
        rmdir public/uploads/pages 2>/dev/null || true
        rmdir public/uploads 2>/dev/null || true
    fi
    
    # Move any other files from nested public
    if [ "$(ls -A public 2>/dev/null)" ]; then
        echo "  Moving other files..."
        mv -n public/* . 2>/dev/null || true
    fi
    
    # Remove the nested public directory
    if rmdir public 2>/dev/null; then
        echo "✓ Removed nested public directory"
    else
        echo "⚠ Could not remove nested public directory (may not be empty)"
        echo "  Contents remaining:"
        ls -la public/
    fi
else
    echo "✓ No nested public directory found"
fi

# Ensure uploads directories exist with correct permissions
echo ""
echo "Checking upload directories..."

if [ ! -d "uploads" ]; then
    mkdir -p uploads
    echo "✓ Created uploads directory"
fi

if [ ! -d "uploads/covers" ]; then
    mkdir -p uploads/covers
    echo "✓ Created uploads/covers directory"
fi

if [ ! -d "uploads/pages" ]; then
    mkdir -p uploads/pages
    echo "✓ Created uploads/pages directory"
fi

# Set correct permissions
chmod 755 uploads
chmod 755 uploads/covers
chmod 755 uploads/pages
echo "✓ Set correct permissions on upload directories"

# List final structure
echo ""
echo "Final structure:"
echo "public/"
ls -la | grep -E "uploads|assets|css|index.php|.htaccess"
echo ""
echo "public/uploads/"
ls -la uploads/

echo ""
echo "=== Fix Complete ==="
echo "You can now test the upload functionality"