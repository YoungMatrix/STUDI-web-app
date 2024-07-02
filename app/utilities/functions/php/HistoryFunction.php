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
use PHPFunctions\Database;
use Classes\History;

/**
 * Class HistoryFunction
 *
 * This class contains functions related to retrieving history records for patients.
 */
class HistoryFunction
{
    /**
     * Retrieve history records for a given patient from the database.
     *
     * @param Database $database The Database instance.
     * @param string $email The email of the patient.
     * @return array|null An array of History objects or null if no records found.
     */
    public static function retrieveHistory($database, $email)
    {
        // Retrieve the patient ID from the database
        $patientId = DatabaseFunction::getValueFromTable($database, 'id_patient', 'patient', 'email_patient', $email);

        // If patient ID is not found, return null
        if (!$patientId) {
            return null;
        }

        // Retrieve history data for the patient using patient ID
        $historyData = DatabaseFunction::getHistoryForPatient($database, $patientId);

        // If no history records found, return null
        if (!$historyData) {
            return null;
        } else {
            $historyRecords = [];

            // Loop through each history record data
            foreach ($historyData as $data) {
                // Retrieve patient's last name using patient ID
                $patientName = DatabaseFunction::getValueFromTable($database, 'last_name_patient', 'patient', 'id_patient', $patientId);

                // Retrieve pattern name using pattern ID from history record
                $patternName = DatabaseFunction::getValueFromTable($database, 'name_pattern', 'pattern', 'id_pattern', $data['id_pattern']);

                // Retrieve field name using field ID from history record
                $fieldName = DatabaseFunction::getValueFromTable($database, 'name_field', 'field', 'id_field', $data['id_field']);

                // Retrieve doctor's last name using doctor ID from history record
                $doctorName = DatabaseFunction::getValueFromTable($database, 'last_name_doctor', 'doctor', 'id_doctor', $data['id_doctor']);

                // If any required information is missing, return null
                if (!$patientName || !$patternName || !$fieldName || !$doctorName) {
                    return null;
                }

                // Create a new History object and add it to the array of history records
                $historyRecord = new History(
                    $patientName,
                    $patternName,
                    $fieldName,
                    $data['id_field'],
                    $doctorName,
                    $data['id_doctor'],
                    $data['date_entrance'],
                    $data['date_release']
                );

                // Add the history record object to the array
                $historyRecords[] = $historyRecord;
            }

            // Return the array of history records
            return $historyRecords;
        }
    }
}
