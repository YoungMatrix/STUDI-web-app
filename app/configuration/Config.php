<?php

// File verified

// Namespace declaration
namespace Configuration;

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
use Classes\Database;
use PHPFunctions\FileHandler;
use PDOException;

/**
 * Central configuration class for the application.
 */
class Config
{
    /**
     * @var string $rootPath Root path of the web server.
     */
    private static $rootPath;

    /**
     * @var string $maintenanceViewPath Path to the maintenance view file.
     */
    private static $maintenanceViewPath;

    /**
     * Initialize configuration by setting root paths, handling maintenance mode, and initializing the database.
     */
    public static function init()
    {
        // Set root and maintenance view paths.
        self::$rootPath = $_SERVER['DOCUMENT_ROOT'];
        self::$maintenanceViewPath = self::$rootPath . '/app/view/maintenance/MaintenanceView.php';

        // Handle maintenance mode or initialize the database.
        self::handleMaintenance();
        self::initializeDatabase();
    }

    /**
     * Initialize database connection using environment variables.
     * If the connection fails, log the error and trigger maintenance mode.
     */
    private static function initializeDatabase()
    {
        try {
            // Load environment variables.
            $dotenv = Dotenv::createImmutable(self::$rootPath . '/app');
            $dotenv->load();

            // Retrieve database connection details from environment variables.
            $dbHost = $_ENV['DB_HOST'];
            $dbRoot = $_ENV['DB_ROOT'];
            $dbName = $_ENV['DB_NAME'];
            $dbPassword = $_ENV['DB_PASSWORD'];
            $dbPort = $_ENV['DB_PORT'];

            // Get the database instance and disconnect to check the connection.
            $database = Database::getInstance($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort);
            $database->disconnect();
        } catch (PDOException $e) {
            // Log the error message and enable maintenance mode.
            error_log('/!\ Connection failed to the Database: ' . $e->getMessage() . ' /!\\');
            FileHandler::maintenanceFile(self::$maintenanceViewPath);
            exit;
        }
    }

    /**
     * Check if maintenance mode is enabled. If enabled, display the maintenance view and exit.
     */
    private static function handleMaintenance()
    {
        try {
            // Load environment variables.
            $dotenv = Dotenv::createImmutable(self::$rootPath . '/app');
            $dotenv->load();

            // Retrieve the maintenance mode status from environment variables.
            $maintenanceModeEnabled = strtolower($_ENV['MAINTENANCE_MODE']) === 'true';

            // If maintenance mode is enabled, display the maintenance view and exit.
            if ($maintenanceModeEnabled) {
                FileHandler::maintenanceFile(self::$maintenanceViewPath);
                exit;
            }
        } catch (\Exception $e) {
            // Log any errors encountered while handling maintenance mode.
            error_log('/!\ Error handling maintenance mode: ' . $e->getMessage() . ' /!\\');
            FileHandler::maintenanceFile(self::$maintenanceViewPath);
            exit;
        }
    }

    /**
     * Retrieves the public reCAPTCHA key from environment variables.
     *
     * @return string Public reCAPTCHA key.
     */
    public static function getPublicReCaptchaKey()
    {
        // Set root path.
        self::$rootPath = $_SERVER['DOCUMENT_ROOT'];

        // Load environment variables.
        $dotenv = Dotenv::createImmutable(self::$rootPath . '/app');
        $dotenv->load();

        // Retrieve database connection details from environment variables.
        $publicReCaptchaKey = $_ENV['PUBLIC_RECAPTCHA_KEY'];

        return $publicReCaptchaKey;
    }

    /**
     * Retrieves the secret reCAPTCHA key from environment variables.
     *
     * @return string Secret reCAPTCHA key.
     */
    public static function getSecretReCaptchaKey()
    {
        // Set root path.
        self::$rootPath = $_SERVER['DOCUMENT_ROOT'];

        // Load environment variables.
        $dotenv = Dotenv::createImmutable(self::$rootPath . '/app');
        $dotenv->load();

        // Retrieve database connection details from environment variables.
        $secretReCaptchaKey = $_ENV['SECRET_RECAPTCHA_KEY'];

        return $secretReCaptchaKey;
    }
}
