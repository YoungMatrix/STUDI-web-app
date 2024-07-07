<?php

// File verified

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Dotenv\Dotenv;
use Test\AdminModelTest;
use Test\DatabaseTest;
use Test\UserModelTest;

try {
    // Creating an instance of Dotenv to load environment variables
    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/app');
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
    $dbHost = $_ENV['DB_HOST'];
    $dbRoot = $_ENV['DB_ROOT'];
    $dbName = $_ENV['DB_NAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbPort = $_ENV['DB_PORT'];

    // Call the function to perform database operations testing
    DatabaseTest::testDatabaseOperations($dbHost, $dbRoot, $dbName, $dbPassword, $dbPort);

    // Call UserModelTest to run all tests related to user model
    UserModelTest::runAllTests();

    // Call AdminModelTest to run all tests related to admin model
    AdminModelTest::runAllTests();

    echo "---------------------------";
} catch (Exception $e) {
    // Handle any exceptions that occur during initialization
    echo "Error during tests: " . $e->getMessage();
}
