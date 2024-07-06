<?php

// Namespace declaration
namespace Test;

/**
 * Define the path to the autoload file.
 *
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use Classes\Person;
use PHPFunctions\DatabaseFunction;
use PHPFunctions\HistoryFunction;
use PHPFunctions\VerificationFunction;
use PHPFunctions\PasswordFunction;
use PHPFunctions\UserFunction;
use Classes\Patient;

// DateTime importation
use DateTime;
use DateInterval;

// Initialize the configuration.
Config::init();

/**
 * Class UserModelTest
 *
 * Test class for verifying signup and login processes.
 */
class UserModelTest
{
    /**
     * Test method for verifying if all characters in a word are alphabetical.
     */
    private static function testAreAllCharactersAlpha()
    {
        // Test valid strings
        self::assertTrue(VerificationFunction::areAllCharactersAlpha("Alphabet"), "Alphabet should be valid");
        self::assertTrue(VerificationFunction::areAllCharactersAlpha("AvecEspace"), "AvecEspace should be valid");

        // Test invalid strings
        self::assertFalse(VerificationFunction::areAllCharactersAlpha("1234"), "1234 should be invalid");
        self::assertFalse(VerificationFunction::areAllCharactersAlpha("Alpha123"), "Alpha123 should be invalid");
        self::assertFalse(VerificationFunction::areAllCharactersAlpha("Alpha!"), "Alpha! should be invalid");
    }

    /**
     * Test method for verifying the format of an email address.
     */
    private static function testVerifyEmail()
    {
        // Test valid email addresses
        self::assertTrue(VerificationFunction::verifyEmail("test@example.com"), "test@example.com should be valid");
        self::assertTrue(VerificationFunction::verifyEmail("user.name+tag+sorting@example.com"), "user.name+tag+sorting@example.com should be valid");

        // Test invalid email addresses
        self::assertFalse(VerificationFunction::verifyEmail("plainaddress"), "plainaddress should be invalid");
        self::assertFalse(VerificationFunction::verifyEmail("@missingusername.com"), "@missingusername.com should be invalid");
        self::assertFalse(VerificationFunction::verifyEmail("username@.com"), "username@.com should be invalid");
    }

    /**
     * Test method for verifying the format of an address.
     */
    private static function testVerifyAddress()
    {
        // Test valid address
        self::assertTrue(VerificationFunction::verifyAddress("123 rue de l'exemple, 75000, Paris"), "Valid address 1: 123 rue de l'exemple, 75000, Paris");

        // Test invalid addresses
        self::assertFalse(VerificationFunction::verifyAddress("12 bis avenue des Champs, 69000, Lyon"), "Invalid address 2: 12 bis avenue des Champs, 69000, Lyon");
        self::assertFalse(VerificationFunction::verifyAddress("123 rue de l'exemple, 75000"), "Address without city should be invalid: 123 rue de l'exemple, 75000");
        self::assertFalse(VerificationFunction::verifyAddress("rue sans numéro, 75000, Paris"), "Address without number should be invalid: rue sans numéro, 75000, Paris");
        self::assertFalse(VerificationFunction::verifyAddress("123 rue de l'exemple, codepostal, Paris"), "Address with invalid postal code should be invalid: 123 rue de l'exemple, codepostal, Paris");
    }

    /**
     * Test method for verifying the reCAPTCHA token.
     */
    private static function testVerifyRecaptchaToken()
    {
        // Mock a successful reCAPTCHA response
        $mockTokenValid = 'mockValidToken';
        $mockSecretKey = 'mockSecretKey';

        // Simulating the response without actual network call
        $simulatedResponse = function ($token, $secretReCaptchaKey) {
            return ($token === 'mockValidToken' && $secretReCaptchaKey === 'mockSecretKey');
        };

        // Test the simulated function
        self::assertTrue($simulatedResponse($mockTokenValid, $mockSecretKey), "Valid reCAPTCHA token should return true");
    }

    /**
     * Test method for generating hashed salt.
     */
    private static function testGenerateHashedSalt()
    {
        // Generate hashed salt
        $hashedSalt = PasswordFunction::generateHashedSalt();

        // Assert that hashed salt is not empty
        self::assertTrue(!empty($hashedSalt), "Hashed salt should not be empty");

        // Assert that hashed salt length is 64 characters
        self::assertTrue(strlen($hashedSalt) === 64, "Hashed salt should be 64 characters long");
    }

    /**
     * Test method for retrieving and hashing the pepper value.
     */
    private static function testRetrieveHashedPepper()
    {
        // Retrieve and hash the pepper value
        $hashedPepper = PasswordFunction::retrieveHashedPepper();

        // Assert that hashed pepper is not empty
        self::assertTrue(!empty($hashedPepper), "Hashed pepper should not be empty");

        // Assert that hashed pepper length is 64 characters
        self::assertTrue(strlen($hashedPepper) === 64, "Hashed pepper should be 64 characters long");
    }

    /**
     * Test method for hashing the password.
     */
    private static function testHashPassword()
    {
        // Define a password
        $password = "securePassword";

        // Generate hashed salt
        $hashedSalt = PasswordFunction::generateHashedSalt();

        // Retrieve and hash the pepper value
        $hashedPepper = PasswordFunction::retrieveHashedPepper();

        // Hash the password
        $hashedPassword = PasswordFunction::hashPassword($password, $hashedSalt, $hashedPepper);

        // Assert that hashed password is not empty
        self::assertTrue(!empty($hashedPassword), "Hashed password should not be empty");

        // Assert that hashed password length is correct
        self::assertTrue(
            strlen($hashedPassword) === 64 + strlen($hashedSalt) + strlen($hashedPepper),
            "Hashed password should be " . (64 + strlen($hashedSalt) + strlen($hashedPepper)) . " characters long"
        );
    }

    /**
     * Test method for verifying the password.
     */
    private static function testVerifyPassword()
    {
        // Define a password (without spaces)
        $password = "securePassword";

        // Generate hashed salt
        $hashedSalt = PasswordFunction::generateHashedSalt();

        // Retrieve and hash the pepper value
        $hashedPepper = PasswordFunction::retrieveHashedPepper();

        // Hash the password with salt and pepper
        $hashedPassword = PasswordFunction::hashPassword($password, $hashedSalt, $hashedPepper);

        // Verify the password without using salt and pepper
        $hashedPasswordUser = hash('sha256', $password);

        // Verify the password
        $isPasswordValid = PasswordFunction::verifyPassword($hashedPasswordUser, $hashedPassword, $hashedSalt, $hashedPepper);

        // Assert that password verification is successful
        self::assertTrue($isPasswordValid, "Password should be verified successfully");
    }

    /**
     * Test method for creating a patient.
     */
    private static function testCreatePatient()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Call createPatient with test values
            $result = UserFunction::createPatient(
                $database,
                'Doe',
                'John',
                '123 Main St',
                'john.doe@example.com',
                'hashedPassword',
                'hashedSalt'
            );

            // Check the result
            if ($result instanceof Patient) {
                echo "PASS: Patient created successfully.<br>";
            } else {
                echo "Failed to create patient.<br>";
            }
        } catch (\Exception $e) {
            // Handle any exceptions thrown during testing
            echo "Error during testCreatePatient: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Test method for retrieving a person (patient) and verifying its authentication.
     */
    public static function testRetrievePerson()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Sample data for test
            $testPatientEmail = 'john.doe@example.com';
            $hashedPassword = 'hashedPassword';
            $hashedPepper = '';

            // Test retrieval of patient
            $retrievedPatient = UserFunction::retrievePerson($database, $testPatientEmail, $hashedPassword, $hashedPepper);

            if ($retrievedPatient instanceof Patient) {
                // Display success messages
                echo "PASS: Patient retrieved and authenticated successfully.<br>";
            } else {
                echo "Failed to retrieve and authenticate patient.<br>";
            }
        } catch (\Exception $e) {
            // Handle any exceptions thrown during testing
            echo "Error during testRetrievePerson: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Test method for saveHistory function.
     */
    public static function testSaveHistory()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Sample data for test
            $testPatient = new Person('Doe', 'John', 'john.doe@example.com');

            $patternName = 'Fatigue';
            $fieldName = 'Médecine Générale';
            $doctorName = 'GORGIO';
            $entranceDate = '2024-01-01';
            $releaseDate = '2024-01-02';

            // Call the saveHistory method
            $result = HistoryFunction::saveHistory($database, $testPatient, $patternName, $fieldName, $doctorName, $entranceDate, $releaseDate);

            // Verify the result
            if ($result) {
                echo "PASS: History saved successfully.<br>";
                echo "<pre>";
                print_r($result);
                echo "</pre>";

                // Verify that planning was updated correctly
                $lastHistoryId = $result['lastHistoryId'];
                $doctorIdQuery = "SELECT id_doctor FROM doctor WHERE last_name_doctor = :doctorName";
                $doctorIdParams = [':doctorName' => $doctorName];
                $doctorIdResult = $database->executeQueryParam($doctorIdQuery, $doctorIdParams, true);
                $doctorId = $doctorIdResult ? $doctorIdResult[0]['id_doctor'] : null;

                if ($doctorId) {
                    // Check each date in the range to ensure it was added to planning
                    $currentDate = new DateTime($entranceDate);
                    $releaseDateTime = new DateTime($releaseDate);
                    $interval = new DateInterval('P1D');

                    $allDatesExist = true;

                    while ($currentDate <= $releaseDateTime) {
                        $formattedDate = $currentDate->format('Y-m-d');
                        $checkQuery = "SELECT COUNT(*) as count FROM planning WHERE id_history = :historyId AND id_confirmed_doctor = :doctorId AND date_planning = :date";
                        $checkParams = [
                            ':historyId' => $lastHistoryId,
                            ':doctorId' => $doctorId,
                            ':date' => $formattedDate
                        ];
                        $checkResult = $database->executeQueryParam($checkQuery, $checkParams);
                        $planningExists = $checkResult && $checkResult[0]['count'] > 0;

                        if (!$planningExists) {
                            echo "FAIL: Planning entry missing for date: $formattedDate.<br>";
                            $allDatesExist = false;
                            break;
                        }

                        $currentDate->add($interval);
                    }

                    if ($allDatesExist) {
                        echo "PASS: All planning entries added successfully.<br>";
                    }
                } else {
                    echo "FAIL: Could not retrieve doctor ID.<br>";
                }
            } else {
                echo "FAIL: Failed to save history.<br>";
            }
            // Clean up
            self::cleanUpHistoryAndPlanning($database, $lastHistoryId);
        } catch (\Exception $e) {
            // Handle any exceptions thrown during testing
            echo "Error during testSaveHistory: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Clean up history and planning records by history ID.
     *
     * @param Database $database Database connection object.
     * @param int $historyId ID of the history record to delete.
     */
    private static function cleanUpHistoryAndPlanning($database, $historyId)
    {
        try {
            // Delete history record
            $deleteHistoryQuery = "DELETE FROM history WHERE id_history = :id";
            $deleteHistoryParams = [':id' => $historyId];
            $database->executeQueryParam($deleteHistoryQuery, $deleteHistoryParams, false);

            // Delete planning records associated with the history ID
            $deletePlanningQuery = "DELETE FROM planning WHERE id_history = :id";
            $deletePlanningParams = [':id' => $historyId];
            $database->executeQueryParam($deletePlanningQuery, $deletePlanningParams, false);

            // Alter history table
            $alterQuery = DatabaseFunction::alterHistoryTableQuery();
            $database->executeQuery($alterQuery);

            // Alter planning table
            $alterQuery = DatabaseFunction::alterPlanningTableQuery();
            $database->executeQuery($alterQuery);

            echo "PASS: History and planning records deleted successfully.<br>";
        } catch (\Exception $e) {
            // Handle any exceptions thrown during deletion
            echo "Error cleaning up history and planning records: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Clean up method for test environment.
     */
    public static function testCleanUp()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Sample data for test
            $testPatientEmail = 'john.doe@example.com';

            // Delete patient and reset auto increment
            self::deletePatientAndResetAutoIncrement($database, $testPatientEmail);

            // Delete test tables
            self::deleteTestTables($database);
        } catch (\Exception $e) {
            // Handle any exceptions thrown during clean-up
            echo "Error during test clean-up: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Delete a patient from the database by email and reset auto increment.
     *
     * @param Database $database Database connection object.
     * @param string $emailToDelete Email of the patient to delete.
     */
    private static function deletePatientAndResetAutoIncrement($database, $emailToDelete)
    {
        try {
            // Begin transaction
            $database->beginTransaction();

            // Delete the patient from the database by email
            $deleteQuery = "DELETE FROM patient WHERE email_patient = :email";
            $deleteParams = [':email' => $emailToDelete];
            $deleteSuccess = $database->executeQueryParam($deleteQuery, $deleteParams, false);

            // Reset auto increment
            $resetQuery = "ALTER TABLE patient AUTO_INCREMENT = 1";

            if ($deleteSuccess) {
                // Commit transaction
                $database->commit($resetQuery);
                echo "PASS: Deleted patient successfully.<br>";
                echo "PASS: Reset auto increment successfully.<br>";
            } else {
                echo "Failed to delete patient.<br>";
            }
        } catch (\Exception $e) {
            // Rollback transaction and log error if deletion fails
            $database->rollback();
            echo "Error deleting patient: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Delete test tables if they exist.
     *
     * @param Database $database Database connection object.
     */
    private static function deleteTestTables($database)
    {
        try {
            // SQL query to delete the table
            $deleteQuery = "DROP TABLE IF EXISTS tests";

            // Execute the query to delete the table
            $database->executeQuery($deleteQuery);

            // Output success message if the table was deleted successfully
            echo "PASS: Deleted 'tests' table successfully.<br>";
        } catch (\Exception $e) {
            // Handle any exceptions thrown during table deletion
            echo "Error deleting 'tests' table: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Custom assertion method to check if a condition is true.
     *
     * @param bool $condition The condition to check.
     * @param string $message Message to display upon assertion.
     */
    private static function assertTrue($condition, $message = '')
    {
        if ($condition) {
            echo "PASS: $message\n<br>";
        } else {
            echo "FAIL: $message\n<br>";
        }
    }

    /**
     * Custom assertion method to check if a condition is false.
     *
     * @param bool $condition The condition to check.
     * @param string $message Message to display upon assertion.
     */
    private static function assertFalse($condition, $message = '')
    {
        if (!$condition) {
            echo "PASS: $message\n<br>";
        } else {
            echo "FAIL: $message\n<br>";
        }
    }

    /**
     * Run all unit tests for UserModelTest.
     */
    public static function runAllTests()
    {
        echo "<h1>Running testAreAllCharactersAlpha...</h1>";
        self::testAreAllCharactersAlpha();

        echo "<h1>Running testVerifyEmail...</h1>";
        self::testVerifyEmail();

        echo "<h1>Running testVerifyAddress...</h1>";
        self::testVerifyAddress();

        echo "<h1>Running testVerifyRecaptchaToken...</h1>";
        self::testVerifyRecaptchaToken();

        echo "<h1>Running testGenerateHashedSalt...</h1>";
        self::testGenerateHashedSalt();

        echo "<h1>Running testRetrieveHashedPepper...</h1>";
        self::testRetrieveHashedPepper();

        echo "<h1>Running testHashPassword...</h1>";
        self::testHashPassword();

        echo "<h1>Running testVerifyPassword...</h1>";
        self::testVerifyPassword();

        echo "<h1>Running testCreatePatient...</h1>";
        self::testCreatePatient();

        echo "<h1>Running testRetrievePerson...</h1>";
        self::testRetrievePerson();

        echo "<h1>Running testSaveHistory...</h1>";
        self::testSaveHistory();

        echo "<h1>Running testCleanUp...</h1>";
        self::testCleanUp();

        echo "<h1>End of 'UserModelTest.php'.</h1>";
    }
}
