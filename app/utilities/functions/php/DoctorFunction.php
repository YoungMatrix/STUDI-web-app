<?php

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
use Classes\Doctor;
use PHPFunctions\PasswordFunction;

/**
 * Class DoctorFunction
 * 
 * Contains functions related to managing doctor records.
 */
class DoctorFunction
{
    /**
     * Create a new doctor and insert their information into the database.
     *
     * This function performs the following steps:
     * - Alters the doctor table structure if necessary.
     * - Converts and formats the doctor's last and first names.
     * - Generates a unique email and hashed password.
     * - Inserts the new doctor into the database.
     * - Creates and returns a Doctor object on success, or null on failure.
     *
     * @param Database $database The Database instance.
     * @param string $lastName The last name of the doctor.
     * @param string $firstName The first name of the doctor.
     * @param string $field The field of specialization of the doctor.
     * @param string $hashedSalt The hashed salt for password hashing.
     * @param string $hashedPepper The hashed pepper for password hashing.
     * @return Doctor|null The newly created Doctor object or null if creation fails.
     */
    public static function createDoctor($database, $lastName, $firstName, $field, $hashedSalt, $hashedPepper)
    {
        try {
            // Alter doctor table structure if necessary
            $alterQuery = DatabaseFunction::alterDoctorTableQuery();
            if ($alterQuery) {
                $database->executeQuery($alterQuery);
            }

            // Convert last name to uppercase
            $upperLastName = mb_strtoupper($lastName, 'UTF-8');

            // Convert last name to lowercase
            $lowerLastName = mb_strtolower($lastName, 'UTF-8');

            // Convert first name to lowercase and capitalize the first letter
            $firstName = ucfirst(mb_strtolower($firstName, 'UTF-8'));

            // Begin transaction
            if (!$database->beginTransaction()) {
                error_log("Error in createDoctor function: Failed to start transaction.");
                return null;
            }

            // Query to get the last doctor ID
            $lastMatricule = DatabaseFunction::getMaxDoctorId($database);

            // If no doctor ID found, rollback transaction and return null
            if (!$lastMatricule) {
                $database->rollback();
                error_log("Error in createDoctor function: Last doctor ID not found.");
                return null;
            }

            // Increment matricule for new doctor
            $matricule = $lastMatricule + 1;

            // Create a secret identifier and email for the doctor
            $secret = $lowerLastName . '.' . $matricule;
            $email = str_replace(' ', '', substr($lowerLastName, 0, 1) . '.' . $secret . '@soignemoi.com');

            // Hash the password
            $hashedPassword = PasswordFunction::hashPassword($secret, $hashedSalt, $hashedPepper);

            // Get doctor role ID and field ID from the database
            $doctorRoleId = DatabaseFunction::getValueFromTable($database, 'id_role', 'role', 'name_role', 'docteur');
            $fieldId = DatabaseFunction::getValueFromTable($database, 'id_field', 'field', 'name_field', $field);

            // If role ID or field ID not found, rollback transaction and return null
            if (!$doctorRoleId || !$fieldId) {
                $database->rollback();
                error_log("Error in createDoctor function: Role ID or field ID not found.");
                return null;
            }

            // Prepare and bind parameters for inserting the new doctor
            $params = [
                ':roleId' => $doctorRoleId,
                ':fieldId' => $fieldId,
                ':lastName' => $upperLastName,
                ':firstName' => $firstName,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':salt' => $hashedSalt
            ];

            // Insert new doctor into the database
            $resultInsert = DatabaseFunction::insertNewDoctor($database, $params);

            // Retrieve the new doctor ID
            $newDoctorId = DatabaseFunction::getNewDoctorId($database, $params);

            if ($resultInsert && $newDoctorId) {
                // Commit transaction and create the Doctor object with all the information
                $database->commit();
                return new Doctor($upperLastName, $firstName, $email, $newDoctorId, $field);
            } else {
                // Rollback transaction and log error if insertion fails
                $database->rollback();
                error_log("Error in createDoctor function: Failed to insert new doctor or retrieve new doctor ID.");
                return null;
            }
        } catch (\PDOException $e) {
            // Log PDO exceptions and rollback transaction
            $database->rollback();
            error_log('PDOException in createDoctor function: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Retrieve doctor records from the database.
     *
     * This function retrieves doctor records from the database and returns an array of Doctor objects.
     *
     * @param Database $database The Database instance used for querying.
     *
     * @return array|null An array of Doctor objects representing the retrieved doctor records, or null if no records found.
     */
    public static function retrieveDoctor($database)
    {
        // Initialize an empty array to store doctor records
        $doctorRecords = [];

        try {
            // Retrieve doctor details from the database using DatabaseFunction::getDoctorDetails
            $doctorData = DatabaseFunction::getDoctorDetails($database);

            // Check if records are found
            if (!$doctorData) {
                return $doctorRecords; // Return empty array if no records found
            }

            // Process each doctor record
            foreach ($doctorData as $data) {
                // Retrieve the field name associated with the doctor using getValueFromTable method of Database class
                $fieldName = DatabaseFunction::getValueFromTable($database, 'name_field', 'field', 'id_field', $data['id_field']);

                // Create a Doctor object using fetched data
                $doctorRecord = new Doctor(
                    $data['last_name_doctor'],
                    $data['first_name_doctor'],
                    $data['email_doctor'],
                    $data['id_doctor'],
                    $fieldName // Field name retrieved from the 'field' table
                );

                // Add the Doctor object to the array of doctor records
                $doctorRecords[] = $doctorRecord;
            }

            // Return the array of Doctor objects
            return $doctorRecords;
        } catch (\Exception $e) {
            // Log any exceptions that occur during data retrieval
            error_log('Error in retrieveDoctor function: ' . $e->getMessage());

            // Return empty list if an exception occurs
            return [];
        }
    }
}
