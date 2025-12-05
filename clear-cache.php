<?php
/**
 * Cache Clear Script for Production Deployment
 * This file should be accessible via web browser after deployment
 * URL: https://yourdomain.com/clear-cache.php
 */

// Security: Only allow execution from specific IPs or add a secret token
$allowedIPs = ['127.0.0.1', '::1']; // Add your IP here
$secretToken = 'HTR2025!Clear'; // Change this to a secure token

// Check if request has valid token
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    die('Access denied. Invalid token.');
}

echo "🚀 Starting cache clear process...\n\n";

// Clear route cache
if (file_exists(__DIR__ . '/bootstrap/cache/routes-v7.php')) {
    unlink(__DIR__ . '/bootstrap/cache/routes-v7.php');
    echo "✅ Route cache cleared\n";
}

// Clear config cache
if (file_exists(__DIR__ . '/bootstrap/cache/config.php')) {
    unlink(__DIR__ . '/bootstrap/cache/config.php');
    echo "✅ Config cache cleared\n";
}

// Clear compiled views
$viewPath = __DIR__ . '/storage/framework/views';
if (is_dir($viewPath)) {
    $files = glob($viewPath . '/*.php');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✅ Compiled views cleared\n";
}

// Clear application cache
$cachePath = __DIR__ . '/storage/framework/cache/data';
if (is_dir($cachePath)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($cachePath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    echo "✅ Application cache cleared\n";
}

echo "\n✨ Cache clear completed successfully!\n";
echo "Now delete this file for security: clear-cache.php\n";
