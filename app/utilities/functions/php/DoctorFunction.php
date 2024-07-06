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

/**
 * Class DoctorFunction
 * 
 * Contains functions related to managing doctor records.
 */
class DoctorFunction
{
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

            // Return null if an exception occurs
            return null;
        }
    }
}
