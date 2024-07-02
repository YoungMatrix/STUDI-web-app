<?php

// File verified

// Namespace declaration
namespace Configuration;

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Dotenv\Dotenv;
use Classes\Database;
use PHPFunctions\FileFunction;
use PDOException;

/**
 * Class Config
 * 
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
     * @var string $pepper Pepper for password hashing.
     */
    private static $pepper;

    /**
     * @var string $publicReCaptchaKey Public reCAPTCHA key.
     */
    private static $publicReCaptchaKey;

    /**
     * @var string $secretReCaptchaKey Secret reCAPTCHA key.
     */
    private static $secretReCaptchaKey;

    /**
     * @var mixed|null $person Instance of the logged-in person, retrieved from the session.
     */
    private static $person;

    /**
     * @var mixed|null $patternList Pattern list, retrieved from the session.
     */
    private static $patternList;

    /**
     * @var mixed|null $fieldList Field list, retrieved from the session.
     */
    private static $fieldList;

    /**
     * @var mixed|null $doctorMap Doctor map, retrieved from the session.
     */
    private static $doctorMap;

    /**
     * @var bool $signupError Flag indicating whether there is an error during signup.
     */
    private static $signupError;

    /**
     * @var bool $loginError Flag indicating whether there is an error during login.
     */
    private static $loginError;

    /**
     * @var mixed $database Database instance.
     */
    private static $database;

    /**
     * Initialize configuration by setting root paths, handling maintenance mode, and initializing the database.
     *
     * @return void
     */
    public static function init()
    {
        // Check if a session is active. If not, start a new session.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set the root path based on the document root of the server.
        self::$rootPath = $_SERVER['DOCUMENT_ROOT'];

        // Define the path for the maintenance view file.
        self::$maintenanceViewPath = self::$rootPath . '/app/view/maintenance/MaintenanceView.php';

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
            self::$pepper = $_ENV['PEPPER'];
            self::$publicReCaptchaKey = $_ENV['PUBLIC_RECAPTCHA_KEY'];
            self::$secretReCaptchaKey = $_ENV['SECRET_RECAPTCHA_KEY'];

            // Retrieve the maintenance mode status from environment variables.
            $maintenanceModeEnabled = strtolower($_ENV['MAINTENANCE_MODE']) === 'true';
        } catch (\Exception $e) {
            // Log the error message and enable maintenance mode.
            error_log('Error during load environment variables: ' . $e->getMessage());
            FileFunction::maintenanceFile(self::$maintenanceViewPath);
            exit;
        }

        // Retrieve the logged-in person's instance from the session, if available.
        if (isset($_SESSION['person'])) {
            self::$person = unserialize($_SESSION['person']);
        } else {
            self::$person = null;
        }

        // Retrieve pattern list from the session, if available.
        if (isset($_SESSION['pattern'])) {
            self::$patternList = $_SESSION['pattern'];
        } else {
            self::$patternList = null;
        }

        // Retrieve field list from the session, if available.
        if (isset($_SESSION['field'])) {
            self::$fieldList = $_SESSION['field'];
        } else {
            self::$fieldList = null;
        }

        // Retrieve doctor map from the session, if available.
        if (isset($_SESSION['doctorMap'])) {
            self::$doctorMap = $_SESSION['doctorMap'];
        } else {
            self::$doctorMap = null;
        }

        // Retrieve signup error from session, if available.
        if (isset($_SESSION['signupError'])) {
            self::$signupError = $_SESSION['signupError'];
        } else {
            self::$signupError = false;
        }

        // Retrieve login error from session, if available.
        if (isset($_SESSION['loginError'])) {
            self::$loginError = $_SESSION['loginError'];
        } else {
            self::$loginError = false;
        }

        // Handle maintenance mode or initialize the database.
        self::handleMaintenance($maintenanceModeEnabled);
        self::initializeDatabase($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort);
    }

    /**
     * Handle maintenance mode.
     *
     * @param bool $maintenanceModeEnabled Indicates whether maintenance mode is enabled.
     * @return void
     */
    private static function handleMaintenance($maintenanceModeEnabled)
    {
        // If maintenance mode is enabled, display the maintenance view and exit.
        if ($maintenanceModeEnabled) {
            FileFunction::maintenanceFile(self::$maintenanceViewPath);
            exit;
        }
    }

    /**
     * Initialize the database.
     *
     * @param string $dbHost Database host.
     * @param string $dbName Database name.
     * @param string $dbRoot Database root username.
     * @param string $dbPassword Database root password.
     * @param int $dbPort Database port.
     * @return void
     */
    private static function initializeDatabase($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort)
    {
        try {
            // Get the database instance and disconnect to check the connection.
            $database = Database::getInstance($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort);
            self::$database = $database;
            $database->disconnect();
        } catch (PDOException $e) {
            // Log the error message and enable maintenance mode.
            error_log('Connection failed to the Database: ' . $e->getMessage());
            FileFunction::maintenanceFile(self::$maintenanceViewPath);
            exit;
        }
    }

    /**
     * Get the root path of the web server.
     *
     * @return string
     */
    public static function getRootPath()
    {
        return self::$rootPath;
    }

    /**
     * Get the path to the maintenance view file.
     *
     * @return string
     */
    public static function getMaintenanceViewPath()
    {
        return self::$maintenanceViewPath;
    }

    /**
     * Get the pepper for password hashing.
     *
     * @return string
     */
    public static function getPepper()
    {
        return self::$pepper;
    }

    /**
     * Get the public reCAPTCHA key.
     *
     * @return string
     */
    public static function getPublicReCaptchaKey()
    {
        return self::$publicReCaptchaKey;
    }

    /**
     * Get the secret reCAPTCHA key.
     *
     * @return string
     */
    public static function getSecretReCaptchaKey()
    {
        return self::$secretReCaptchaKey;
    }

    /**
     * Get the instance of the logged-in person.
     *
     * @return mixed|null
     */
    public static function getPerson()
    {
        return self::$person;
    }

    /**
     * Get the pattern list.
     *
     * @return mixed|null
     */
    public static function getPatternList()
    {
        return self::$patternList;
    }

    /**
     * Get the field list.
     *
     * @return mixed|null
     */
    public static function getFieldList()
    {
        return self::$fieldList;
    }

    /**
     * Get the doctor map.
     *
     * @return mixed|null
     */
    public static function getDoctorMap()
    {
        return self::$doctorMap;
    }

    /**
     * Get the signup error status.
     *
     * @return bool
     */
    public static function getSignupError()
    {
        return self::$signupError;
    }

    /**
     * Get the login error status.
     *
     * @return bool
     */
    public static function getLoginError()
    {
        return self::$loginError;
    }

    /**
     * Get the database instance.
     *
     * @return mixed
     */
    public static function getDatabase()
    {
        return self::$database;
    }

    /**
     * Set the logged-in person instance in the session.
     *
     * @param mixed $person The person object to store in session.
     */
    public static function setPerson($person)
    {
        $_SESSION['person'] = serialize($person);
        self::$person = $person;
    }

    /**
     * Set the pattern list in the session.
     *
     * @param array $patternList The pattern list to store in session.
     */
    public static function setPatternList($patternList)
    {
        $_SESSION['pattern'] = $patternList;
        self::$patternList = $patternList;
    }

    /**
     * Set the field list in the session.
     *
     * @param array $fieldList The field list to store in session.
     */
    public static function setFieldList($fieldList)
    {
        $_SESSION['field'] = $fieldList;
        self::$fieldList = $fieldList;
    }

    /**
     * Set the doctor map in the session.
     *
     * @param array $doctorMap The doctor map to store in session.
     */
    public static function setDoctorMap($doctorMap)
    {
        $_SESSION['doctorMap'] = $doctorMap;
        self::$doctorMap = $doctorMap;
    }

    /**
     * Set the signup error in the session.
     *
     * @param bool $signupError The signup error to store in session.
     */
    public static function setSignupError($signupError)
    {
        $_SESSION['signupError'] = $signupError;
        self::$signupError = $signupError;
    }

    /**
     * Set the login error in the session.
     *
     * @param bool $loginError The login error to store in session.
     */
    public static function setLoginError($loginError)
    {
        $_SESSION['loginError'] = $loginError;
        self::$loginError = $loginError;
    }
}
