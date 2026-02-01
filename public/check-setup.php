<?php
// Simple diagnostic script
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup Check</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>Laravel Setup Diagnostic</h1>
    
    <h2>PHP Information</h2>
    <p>PHP Version: <strong><?= phpversion() ?></strong></p>
    <p>Current Directory: <strong><?= __DIR__ ?></strong></p>
    <p>Parent Directory: <strong><?= dirname(__DIR__) ?></strong></p>
    
    <h2>File Structure</h2>
    <?php
    $laravelRoot = dirname(__DIR__);
    $files = ['artisan', '.env', '.env.example', 'composer.json', 'storage', 'bootstrap/cache'];
    
    foreach ($files as $file) {
        $path = $laravelRoot . '/' . $file;
        $exists = file_exists($path);
        $class = $exists ? 'success' : 'error';
        $status = $exists ? '✅' : '❌';
        
        echo "<p class='$class'>$status $file ";
        
        if ($exists) {
            if (is_dir($path)) {
                echo "(directory, " . (is_writable($path) ? "writable" : "<span class='error'>NOT writable</span>") . ")";
            } else {
                echo "(file exists)";
            }
        } else {
            echo "(NOT FOUND)";
        }
        
        echo "</p>";
    }
    ?>
    
    <h2>PHP Extensions</h2>
    <?php
    $required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
    foreach ($required as $ext) {
        $loaded = extension_loaded($ext);
        $class = $loaded ? 'success' : 'error';
        $status = $loaded ? '✅' : '❌';
        echo "<p class='$class'>$status $ext</p>";
    }
    ?>
    
    <h2>Environment File Content (if exists)</h2>
    <?php
    $envPath = $laravelRoot . '/.env';
    if (file_exists($envPath)) {
        $env = file_get_contents($envPath);
        $lines = explode("\n", $env);
        echo "<pre>";
        foreach ($lines as $line) {
            // Hide sensitive values
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                if (in_array(trim($key), ['DB_PASSWORD', 'APP_KEY', 'MAIL_PASSWORD'])) {
                    echo $key . "=***hidden***\n";
                } else {
                    echo htmlspecialchars($line) . "\n";
                }
            } else {
                echo htmlspecialchars($line) . "\n";
            }
        }
        echo "</pre>";
    } else {
        echo "<p class='error'>❌ .env file not found!</p>";
    }
    ?>
    
    <h2>Next Steps</h2>
    <ol>
        <li>Ensure .env file exists with correct database credentials</li>
        <li>Set storage and bootstrap/cache permissions to 777</li>
        <li>Run deployment commands via Terminal or cron jobs</li>
    </ol>
</body>
</html>
