<?php
/**
 * cPanel Deployment Helper Script
 * Upload this file to your cPanel and run via browser: https://yourdomain.com/deploy-cpanel.php
 * 
 * SECURITY: Delete this file after deployment is complete!
 */

// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set password for security
define('DEPLOY_PASSWORD', 'change-this-password-123');

// Check password
if (!isset($_GET['password']) || $_GET['password'] !== DEPLOY_PASSWORD) {
    die('Unauthorized. Add ?password=your-password to URL');
}

echo "<pre>";
echo "=== cPanel Deployment Script ===\n\n";

// Laravel root is one level up from public folder
$laravelRoot = dirname(__DIR__);
chdir($laravelRoot);

echo "Working directory: " . getcwd() . "\n";
echo "PHP Version: " . phpversion() . "\n\n";

// Check prerequisites
echo "=== Checking Prerequisites ===\n";

if (!file_exists('artisan')) {
    die("❌ ERROR: artisan file not found. Make sure you're in the Laravel root directory.\n");
}
echo "✅ artisan file found\n";

if (!file_exists('.env')) {
    echo "⚠️  WARNING: .env file not found!\n";
    echo "Please create .env file first. Copy from .env.example and update:\n";
    echo "- APP_KEY (run: php artisan key:generate)\n";
    echo "- Database credentials\n";
    echo "- APP_URL=https://sindanganom.com\n\n";
    
    if (!isset($_GET['force'])) {
        die("Add &force=1 to URL to continue anyway (not recommended).\n");
    }
} else {
    echo "✅ .env file found\n";
}

if (!is_writable('storage')) {
    echo "⚠️  WARNING: storage directory is not writable\n";
} else {
    echo "✅ storage directory is writable\n";
}

if (!is_writable('bootstrap/cache')) {
    echo "⚠️  WARNING: bootstrap/cache directory is not writable\n";
} else {
    echo "✅ bootstrap/cache directory is writable\n";
}

echo "\n";

// Function to run command and display output
function runCommand($command, $description) {
    echo ">> {$description}\n";
    echo "$ {$command}\n";
    
    $output = [];
    $returnVar = 0;
    exec($command . ' 2>&1', $output, $returnVar);
    
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    if ($returnVar !== 0) {
        echo "❌ FAILED (Exit code: {$returnVar})\n\n";
        return false;
    }
    
    echo "✅ SUCCESS\n\n";
    return true;
}

// Deployment steps
$steps = [
    ['php artisan config:clear', 'Clear configuration cache'],
    ['php artisan cache:clear', 'Clear application cache'],
    ['php artisan view:clear', 'Clear view cache'],
    ['php artisan route:clear', 'Clear route cache'],
    ['php artisan migrate --force', 'Run database migrations'],
    ['php artisan db:seed --force', 'Seed database'],
    ['php artisan storage:link', 'Create storage symlink'],
    ['php artisan config:cache', 'Cache configuration'],
    ['php artisan route:cache', 'Cache routes'],
    ['php artisan view:cache', 'Cache views'],
    ['php artisan icons:cache', 'Cache icons'],
];

// Check if .env exists
if (!file_exists('.env')) {
    echo "⚠️  WARNING: .env file not found!\n";
    echo "Please create .env file before running deployment.\n\n";
} else {
    echo "✅ .env file found\n\n";
}

// Run deployment steps
$success = true;
foreach ($steps as $step) {
    if (!runCommand($step[0], $step[1])) {
        $success = false;
        echo "Deployment stopped due to error.\n";
        break;
    }
}

if ($success) {
    echo "\n=== Deployment Complete! ===\n";
    echo "✅ All steps completed successfully\n\n";
    echo "⚠️  IMPORTANT: Delete this file for security!\n";
    echo "Run: rm deploy-cpanel.php\n";
}

// Display file permissions
echo "\n=== Checking Permissions ===\n";
$dirs = ['storage', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    if (file_exists($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "{$dir}: {$perms}\n";
    }
}

echo "\n</pre>";
