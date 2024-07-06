<?php

// File verified

// Namespace declaration
namespace PHPFunctions;

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use Classes\Patient;
use Classes\Admin;

/**
 * Class UserFunction
 * 
 * Contains functions related to user authentication and retrieval.
 */
class UserFunction
{
    /**
     * Creates a new patient in the database.
     *
     * @param Database $database The Database instance.
     * @param string $lastName The last name of the patient.
     * @param string $firstName The first name of the patient.
     * @param string $address The address of the patient.
     * @param string $email The email of the patient.
     * @param string $hashedPassword The hashed password of the patient.
     * @param string $hashedSalt The hashed salt for password hashing.
     * @return Patient|null Returns a Patient object if creation is successful, otherwise returns null.
     */
    public static function createPatient($database, $lastName, $firstName, $address, $email, $hashedPassword, $hashedSalt)
    {
        try {
            // Convert last name to uppercase
            $upperLastName = mb_strtoupper($lastName, 'UTF-8');

            // Convert first name to lowercase and capitalize the first letter
            $firstName = ucfirst(mb_strtolower($firstName, 'UTF-8'));

            // Convert email to lowercase without space
            $lowerEmail = str_replace(' ', '', mb_strtolower($email, 'UTF-8'));

            // Convert address to lowercase
            $lowerAddress = mb_strtolower($address, 'UTF-8');

            // Begin transaction
            $transactionStarted = $database->beginTransaction();

            if ($transactionStarted) {
                // Get patient role ID from the database
                $patientRoleId = DatabaseFunction::getValueFromTable($database, 'id_role', 'role', 'name_role', 'patient');

                // If no patient role ID is found, rollback transaction and log an error
                if (!$patientRoleId) {
                    $database->rollback();
                    error_log("Patient role ID not found.");
                    return null;
                }

                // Check if the email already exists in the database
                $resultCheckEmail = DatabaseFunction::getPatientCountByEmail($database, $email);

                // If email already exists, rollback transaction and log an error
                if (!$resultCheckEmail) {
                    $database->rollback();
                    error_log("This patient already exists.");
                    return null;
                }

                // Prepare parameters for patient insertion
                $params = [
                    ':roleId' => $patientRoleId,
                    ':name' => $upperLastName,
                    ':firstName' => $firstName,
                    ':address' => $lowerAddress,
                    ':email' => $lowerEmail,
                    ':password' => $hashedPassword,
                    ':salt' => $hashedSalt
                ];

                // Insert new patient record
                $resultInsert = DatabaseFunction::insertNewPatient($database, $params);

                // Assuming the `alterPatientTableQuery` function needs to be called after insert
                $alterQuery = DatabaseFunction::alterPatientTableQuery();

                // Check if insertion and alter query were successful
                if ($resultInsert && $alterQuery) {
                    // Commit transaction if both operations were successful
                    $database->commit($alterQuery);
                    // Return a new Patient object upon successful creation
                    $patient = new Patient($upperLastName, $firstName, $lowerEmail, $lowerAddress);
                    return $patient;
                } else {
                    // Rollback transaction if any operation failed
                    $database->rollback($alterQuery);
                    error_log("Failed to insert new patient.");
                    return null;
                }
            } else {
                // Rollback transaction if transaction couldn't be started
                $database->rollback();
                echo "Failed to start transaction in createPatient function.<br>";
                return null;
            }
        } catch (\PDOException $e) {
            // Log PDO exceptions
            error_log('PDOException: ' . $e->getMessage());

            // Return null in case of any exception
            return null;
        }
    }

    /**
     * Function to verify the email and password for user authentication.
     *
     * This function prepares and executes an SQL query to retrieve a user from the database based on their email.
     * It then verifies the hashed password by comparing it with the hashed password stored in the database.
     * If the password matches, it creates a new user object and returns it; otherwise, it returns null.
     *
     * @param Database $database The Database instance.
     * @param string $email The email of the user.
     * @param string $hashedPassword The hashed password entered by the user.
     * @param string $hashedPepper The hashed pepper used for password hashing.
     * @param array|null $historyRecords Optional. An array of history records associated with the user.
     * @return Patient|Admin|null Returns a Patient or Admin object if authentication is successful, otherwise returns null.
     */
    public static function retrievePerson($database, $email, $hashedPassword, $hashedPepper, $historyRecords = null)
    {
        try {
            // Convert email to lowercase
            $lowerEmail = mb_strtolower($email, 'UTF-8');

            // Attempt to retrieve patient data from the database based on email
            $patientData = DatabaseFunction::getPatientDataByEmail($database, $lowerEmail);

            // If patient record found and password matches
            if ($patientData !== null && count($patientData) > 0) {
                $patient = $patientData[0]; // Fetch the first result

                // Verify the hashed password against stored password using salt and pepper
                if (PasswordFunction::verifyPassword($hashedPassword, $patient['password_patient'], $patient['salt_patient'], $hashedPepper)) {
                    // Create a new Patient object and return it
                    return new Patient(
                        $patient['last_name_patient'],
                        $patient['first_name_patient'],
                        $patient['email_patient'],
                        $patient['address_patient'],
                        $historyRecords
                    );
                }
            }

            // Attempt to retrieve admin data from the database based on email
            $adminData = DatabaseFunction::getAdminByEmail($database, $lowerEmail);

            // If admin record found and password matches
            if ($adminData !== null && count($adminData) > 0) {
                $admin = $adminData[0]; // Fetch the first result

                // Verify the hashed password against stored password using salt and pepper
                if (PasswordFunction::verifyPassword($hashedPassword, $admin['password_admin'], $admin['salt_admin'], $hashedPepper)) {
                    // Retrieve doctor records and store them in session and class property
                    Config::setDoctorRecords(DoctorFunction::retrieveDoctor($database));

                    // Create a new Admin object and return it
                    return new Admin(
                        $admin['last_name_admin'],
                        $admin['first_name_admin'],
                        $admin['email_admin']
                    );
                }
            }

            // If no user found or password does not match, return null
            return null;
        } catch (\PDOException $e) {
            // Log PDO exceptions
            error_log('PDOException: ' . $e->getMessage());

            // Return null in case of any exception
            return null;
        }
    }
}
