<?php
// Simple cache clear for production
// Access: http://rollershuttersingapore.com/clear.php?token=HTR2025!Clear

if (!isset($_GET['token']) || $_GET['token'] !== 'HTR2025!Clear') {
    http_response_code(403);
    die('Access denied');
}

echo "Starting cache clear...\n\n";

// Clear route cache
$routeCache = __DIR__ . '/../bootstrap/cache/routes-v7.php';
if (file_exists($routeCache)) {
    unlink($routeCache);
    echo "✅ Route cache cleared\n";
}

// Clear config cache
$configCache = __DIR__ . '/../bootstrap/cache/config.php';
if (file_exists($configCache)) {
    unlink($configCache);
    echo "✅ Config cache cleared\n";
}

// Clear view cache
$viewPath = __DIR__ . '/../storage/framework/views';
if (is_dir($viewPath)) {
    foreach (glob($viewPath . '/*.php') as $file) {
        unlink($file);
    }
    echo "✅ View cache cleared\n";
}

echo "\n✨ Cache cleared successfully!\n";
echo "Now test your service links.";
