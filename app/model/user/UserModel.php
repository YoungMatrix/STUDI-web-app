<?php

// File verified

// Namespace declaration
namespace UserModel;

// DateTime importation
use DateTime;

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use PHPFunctions\RecaptchaFunction;
use PHPFunctions\PasswordFunction;
use PHPFunctions\HistoryFunction;
use PHPFunctions\UserFunction;
use PHPFunctions\DatabaseFunction;
use PHPFunctions\VerificationFunction;

// Initialize the configuration.
Config::init();

/**
 * Class UserModel
 * 
 * UserModel class handles user authentication and session management.
 * It sanitizes input fields, verifies reCAPTCHA token, retrieves user history,
 * hashes passwords, retrieves user from database, and manages session data.
 */
class UserModel
{
    /**
     * Process user signup attempt.
     *
     * Sanitizes and validates input fields, verifies reCAPTCHA token,
     * hashes passwords, creates a new patient in the database,
     * and manages session variables if signup is successful.
     */
    public static function processSignup()
    {
        // Sanitize and validate input fields
        $safeVisitorLastName = htmlspecialchars($_POST['visitorLastName'], ENT_QUOTES, 'UTF-8');
        $safeVisitorFirstName = htmlspecialchars($_POST['visitorFirstName'], ENT_QUOTES, 'UTF-8');
        $safeVisitorAddress = htmlspecialchars($_POST['visitorAddress'], ENT_QUOTES, 'UTF-8');
        $safeVisitorEmail = htmlspecialchars($_POST['visitorEmail'], ENT_QUOTES, 'UTF-8');
        $safeVisitorPassword = htmlspecialchars($_POST['visitorPassword'], ENT_QUOTES, 'UTF-8');
        $safeRecaptchaToken = htmlspecialchars($_POST['g-recaptcha-response-signup'], ENT_QUOTES, 'UTF-8');

        // Validate input fields and recaptcha token
        $successLastName = VerificationFunction::areAllCharactersAlpha($safeVisitorLastName);
        $successFirstName = VerificationFunction::areAllCharactersAlpha($safeVisitorFirstName);
        $successEmail = VerificationFunction::verifyEmail($safeVisitorEmail);
        $successAddress = VerificationFunction::verifyAddress($safeVisitorAddress);
        $successRecaptchaToken = RecaptchaFunction::verifyRecaptchaToken($safeRecaptchaToken, Config::getSecretReCaptchaKey());

        // Generate salt for password hashing
        $hashedSalt = PasswordFunction::generateHashedSalt();

        // Retrieve hashed pepper from configuration
        $hashedPepper = PasswordFunction::retrieveHashedPepper();

        // Hash the password
        $hashedPassword = PasswordFunction::hashPassword($safeVisitorPassword, $hashedSalt, $hashedPepper);

        // Log error messages for invalid input fields or recaptcha token
        if (!$successLastName) {
            error_log('Last name is not valid.');
        }

        if (!$successFirstName) {
            error_log('First name is not valid.');
        }

        if (!$successEmail) {
            error_log('Email is not valid.');
        }

        if (!$successAddress) {
            error_log('Address is not valid.');
        }

        if (!$successRecaptchaToken) {
            error_log('Recaptcha token is not valid.');
        }

        // If all input fields and recaptcha token are valid, attempt to create a new patient
        if ($successLastName && $successFirstName && $successEmail && $successAddress && $successRecaptchaToken) {
            // Create the person in the database
            $person = UserFunction::createPatient(Config::getDatabase(), $safeVisitorLastName, $safeVisitorFirstName, $safeVisitorAddress, $safeVisitorEmail, $hashedPassword, $hashedSalt);

            // If patient creation is successful, set session variables
            if ($person !== null) {
                // Storing the patient object in the session
                Config::setPerson($person);

                // Storing pattern lists retrieved from the database in the session
                Config::setPatternList(DatabaseFunction::getPatternList(Config::getDatabase()));

                // Storing field lists retrieved from the database in the session
                Config::setFieldList(DatabaseFunction::getFieldList(Config::getDatabase()));

                // Storing doctor map data retrieved from the database in the session
                Config::setDoctorMap(DatabaseFunction::getDoctorMap(Config::getDatabase()));
            } else {
                error_log("Failed to create person in the database.");
            }
        } else {
            // Log error if input fields or recaptcha token are not valid
            error_log('One or more input fields (last name, first name, email, address) or recaptcha token are not valid.');
        }
    }

    /**
     * Process user login attempt.
     *
     * Sanitizes input, verifies reCAPTCHA token, retrieves user history,
     * hashes password, retrieves user from database, and manages session data.
     */
    public static function processLogin()
    {
        // Sanitize input fields
        $safeUserEmail = htmlspecialchars($_POST['userEmail'], ENT_QUOTES, 'UTF-8');
        $safeUserPassword = htmlspecialchars($_POST['userPassword'], ENT_QUOTES, 'UTF-8');
        $safeRecaptchaToken = htmlspecialchars($_POST['g-recaptcha-response-login'], ENT_QUOTES, 'UTF-8');

        // Verify reCAPTCHA token
        $successRecaptchaToken = RecaptchaFunction::verifyRecaptchaToken($safeRecaptchaToken, Config::getSecretReCaptchaKey());

        // If recaptcha token is valid, attempt to retrieve patient
        if ($successRecaptchaToken) {
            // Retrieve history records for the patient from the database.
            $historyRecords = HistoryFunction::retrieveHistory(Config::getDatabase(), $safeUserEmail);

            // Log error if history records retrieval failed
            if (!$historyRecords) {
                if ($historyRecords === []) {
                    error_log("No history record in the database.");
                } else {
                    error_log("Failed to retrieve person's history from the database.");
                }
            }

            // Hash the user password
            $hashedPassword = hash('sha256', $safeUserPassword);

            // Retrieve the pepper used for password hashing.
            $hashedPepper = PasswordFunction::retrieveHashedPepper();

            // Retrieve the person from the database.
            $person = UserFunction::retrievePerson(Config::getDatabase(), $safeUserEmail, $hashedPassword, $hashedPepper, $historyRecords);

            // Check if person retrieval was successful
            if ($person !== null) {
                // Storing the patient object in the session
                Config::setPerson($person);

                // Storing pattern lists retrieved from the database in the session
                Config::setPatternList(DatabaseFunction::getPatternList(Config::getDatabase()));

                // Storing field lists retrieved from the database in the session
                Config::setFieldList(DatabaseFunction::getFieldList(Config::getDatabase()));

                // Storing doctor map data retrieved from the database in the session
                Config::setDoctorMap(DatabaseFunction::getDoctorMap(Config::getDatabase()));
            } else {
                // Log error if person retrieval failed
                error_log("Failed to retrieve person from the database.");
            }
        } else {
            // Log error if recaptcha token is not valid
            error_log('Recaptcha token not valid.');
        }
    }

    /**
     * Add an appointment based on sanitized input fields.
     */
    public static function addAppointment()
    {
        // Sanitize input fields
        $safePattern = htmlspecialchars($_POST['pattern'], ENT_QUOTES, 'UTF-8');
        $safeField = htmlspecialchars($_POST['field'], ENT_QUOTES, 'UTF-8');
        $safeDoctor = htmlspecialchars($_POST['doctor'], ENT_QUOTES, 'UTF-8');

        // Format dates and validate
        $today = new DateTime(); // Get current date and time
        $formattedentranceDate = DateTime::createFromFormat('Y-m-d', $_POST['entranceDate']); // Format entrance date
        $formattedreleaseDate = DateTime::createFromFormat('Y-m-d', $_POST['releaseDate']); // Format release date
        $safeentranceDate = htmlspecialchars($_POST['entranceDate'], ENT_QUOTES, 'UTF-8'); // Sanitize entrance date
        $safereleaseDate = htmlspecialchars($_POST['releaseDate'], ENT_QUOTES, 'UTF-8'); // Sanitize release date

        // Save history if dates are valid
        if ($formattedentranceDate < $formattedreleaseDate && $today < $formattedentranceDate) {
            // Get logged-in person
            $person = Config::getPerson();

            // Save history to database
            $savedHistory = HistoryFunction::saveHistory(Config::getDatabase(), $person, $safePattern, $safeField, $safeDoctor, $safeentranceDate, $safereleaseDate);

            // Update session variables
            Config::setSavedHistory($savedHistory);

            if ($savedHistory) {
                // Retrieve updated history records
                $historyRecords = HistoryFunction::retrieveHistory(Config::getDatabase(), $person->getEmail());
                $person->setExtendedInformation($historyRecords);

                // Update session variables
                Config::setPerson($person);
            } else {
                // Log error if history saving fails
                error_log("Failed to save history in the database.");
            }
        } else {
            // Log error if dates are not valid
            error_log('Entrance and release dates are not valid.');
        }
    }
}
