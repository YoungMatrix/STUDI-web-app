<?php

// File verified

/**
 * Define the root path of the web server and the path to the autoload file.
 * 
 * @var string $rootPath Root path of the web server.
 * @var string $autoload Path to the autoload file.
 */
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$autoload = $rootPath . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Dotenv\Dotenv;
use Test\DatabaseTest;

try {
    // Creating an instance of Dotenv to load environment variables
    $dotenv = Dotenv::createImmutable($rootPath . '/app');
    $dotenv->load();

    /**
     * Retrieving environment variables from $_ENV
     *
     * @var string $dbHost The host name for the database connection.
     * @var string $dbRoot The username (root) for the database connection.
     * @var string $dbName The name of the database to connect to.
     * @var string $dbPassword The password for the database user.
     * @var string|null $dbPort The port number for the database connection (can be null).
     */
    $dbHost = $_ENV['DB_HOST_TEST'];
    $dbRoot = $_ENV['DB_ROOT_TEST'];
    $dbName = $_ENV['DB_NAME_TEST'];
    $dbPassword = $_ENV['DB_PASSWORD_TEST'];
    $dbPort = $_ENV['DB_PORT_TEST'];

    // Call the function to perform database operations testing
    DatabaseTest::testDatabaseOperations($dbHost, $dbRoot, $dbName, $dbPassword, $dbPort);
} catch (Exception $e) {
    // Handle any exceptions that occur during initialization
    echo "Error during database initialization: " . $e->getMessage();
}
