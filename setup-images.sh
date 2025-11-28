#!/bin/bash

# Image Management System - Setup Script
# This script sets up the image storage system for both local and shared hosting

echo "=========================================="
echo "Image Management System Setup"
echo "=========================================="
echo ""

# Check PHP version
echo "Checking PHP version..."
php -v | head -n 1

# Create storage directories
echo ""
echo "Creating storage directories..."
mkdir -p storage/app/public/{services,projects,testimonials,about}
echo "✓ Storage directories created"

# Create public/uploads directories (for shared hosting)
echo ""
echo "Creating public/uploads directories..."
mkdir -p public/uploads/{services,projects,testimonials,about}
echo "✓ Public uploads directories created"

# Set permissions
echo ""
echo "Setting directory permissions..."
chmod -R 755 storage/app/public
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chmod -R 755 public/uploads
echo "✓ Permissions set"

# Create symlink
echo ""
echo "Creating storage symlink..."
if [ -L "public/storage" ]; then
    echo "⚠ Symlink already exists"
    ls -la public/storage
else
    ln -s ../storage/app/public public/storage
    if [ -L "public/storage" ]; then
        echo "✓ Symlink created successfully"
        ls -la public/storage
    else
        echo "✗ Failed to create symlink"
        echo "  Try running: php artisan storage:link"
    fi
fi

# Verify directories
echo ""
echo "Verifying directory structure..."
echo "Storage directories:"
ls -la storage/app/public/ | grep "^d"
echo ""
echo "Public uploads directories:"
ls -la public/uploads/ | grep "^d"

# Check permissions
echo ""
echo "Checking write permissions..."
if [ -w "storage" ]; then
    echo "✓ storage/ is writable"
else
    echo "✗ storage/ is NOT writable"
fi

if [ -w "public/uploads" ]; then
    echo "✓ public/uploads/ is writable"
else
    echo "✗ public/uploads/ is NOT writable"
fi

if [ -w "bootstrap/cache" ]; then
    echo "✓ bootstrap/cache/ is writable"
else
    echo "✗ bootstrap/cache/ is NOT writable"
fi

echo ""
echo "=========================================="
echo "Setup complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Run: php artisan migrate"
echo "2. Run: php artisan db:seed (optional)"
echo "3. Start server: php artisan serve"
echo "4. Test image upload in admin panel"
echo ""
