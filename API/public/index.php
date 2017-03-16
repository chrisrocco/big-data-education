<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require __DIR__ . '/../vendor/autoload.php';



// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/src/Database/Database.php';
require __DIR__ . '/../app/src/Accounts/Accounts.php';
require __DIR__ . '/../app/src/Assignments/Assignments.php';
require __DIR__ . '/../app/src/Conflicts/ConflictManager.php';
require __DIR__ . '/../app/dependencies.php';


// Register middleware
require __DIR__ . '/../app/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run!
$app->run();
