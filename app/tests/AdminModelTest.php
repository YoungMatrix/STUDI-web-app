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
use Classes\Doctor;
use PHPFunctions\DoctorFunction;

// Initialize the configuration.
Config::init();

/**
 * Class AdminModelTest
 *
 * This class contains unit tests for the AdminModel.
 * It includes methods to test the creation of doctors and other admin-related functionalities.
 */
class AdminModelTest
{
    /**
     * Test method for creating a doctor.
     */
    public static function testCreateDoctor()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Call createDoctor with test values
            $result = DoctorFunction::createDoctor(
                $database,
                'SMITH',
                'John',
                'Chirurgie',
                'hashedSalt',
                'hashedPepper'
            );

            // Check the result
            if ($result instanceof Doctor) {
                echo "PASS: Doctor created successfully.<br>";
                echo "Doctor Matricule: " . $result->getMatricule() . "<br>";
                echo "Doctor Name: " . $result->getFirstName() . " " . $result->getLastName() . "<br>";
                echo "Doctor Email: " . $result->getEmail() . "<br>";
                echo "Doctor Field: " . $result->getField() . "<br>";
            } else {
                echo "Failed to create doctor.<br>";
            }

            // Sample data for test
            $testDoctorEmail = $result->getEmail();

            // Delete doctor and reset auto increment
            self::deleteDoctorAndResetAutoIncrement($database, $testDoctorEmail);
        } catch (\Exception $e) {
            // Handle any exceptions thrown during testing
            echo "Error during testCreateDoctor: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Delete a doctor from the database by name and reset auto increment.
     *
     * @param Database $database Database connection object.
     * @param string $emailToDelete Email of the doctor to delete.
     */
    private static function deleteDoctorAndResetAutoIncrement($database, $emailToDelete)
    {
        try {
            // Begin transaction
            $database->beginTransaction();

            // Delete the doctor from the database by email
            $deleteQuery = "DELETE FROM doctor WHERE email_doctor = :email";
            $deleteParams = [':email' => $emailToDelete];
            $deleteSuccess = $database->executeQueryParam($deleteQuery, $deleteParams, false);

            // Reset auto increment
            $resetQuery = "ALTER TABLE doctor AUTO_INCREMENT = 1";

            if ($deleteSuccess) {
                // Commit transaction
                $database->commit($resetQuery);
                echo "PASS: Deleted doctor successfully.<br>";
                echo "PASS: Reset auto increment successfully.<br>";
            } else {
                echo "Failed to delete doctor.<br>";
            }
        } catch (\Exception $e) {
            // Rollback transaction and log error if deletion fails
            $database->rollback();
            echo "Error deleting doctor: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Run all unit tests for AdminModelTest.
     */
    public static function runAllTests()
    {
        echo "<h1>Running testCreateDoctor...</h1>";
        self::testCreateDoctor();

        echo "<h1>End of 'AdminModelTest.php'.</h1>";
    }
}
