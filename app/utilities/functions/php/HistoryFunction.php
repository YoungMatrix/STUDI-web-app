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
use PHPFunctions\DatabaseFunction;
use PHPFunctions\PlanningFunction;
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

    /**
     * Save history records for a given patient in the database.
     *
     * This function saves the history records for a specific patient in the database.
     * It checks for any existing entries within the specified date range and inserts
     * a new record if no similar entry exists. It also updates the planning table
     * with the corresponding history ID and dates.
     *
     * @param Database $database The Database instance.
     * @param Patient $person The patient object.
     * @param string $patternName The name of the pattern.
     * @param string $fieldName The name of the field.
     * @param string $doctorName The name of the doctor.
     * @param string $entranceDate The entrance date in YYYY-MM-DD format.
     * @param string $releaseDate The release date in YYYY-MM-DD format.
     * @return array|null The saved history data or null if saving fails.
     */
    public static function saveHistory($database, $person, $patternName, $fieldName, $doctorName, $entranceDate, $releaseDate)
    {
        try {
            // Alter history table structure if necessary
            $alterQuery = DatabaseFunction::alterHistoryTableQuery();
            if ($alterQuery) {
                $database->executeQuery($alterQuery);
            }

            // Alter planning table structure if necessary
            $alterQuery = DatabaseFunction::alterPlanningTableQuery();
            if ($alterQuery) {
                $database->executeQuery($alterQuery);
            }

            // Store the email of the person in a variable
            $personEmail = $person->getEmail();

            // Begin transaction
            $transactionStarted = $database->beginTransaction();

            if ($transactionStarted) {
                // Get the patient ID based on the email
                $patientId = DatabaseFunction::getValueFromTable($database, 'id_patient', 'patient', 'email_patient', $personEmail);

                if (!$patientId) {
                    // Rollback transaction and log error if patient ID not found
                    $database->rollback();
                    error_log("Patient ID not found for email: $personEmail");
                    return null;
                }

                // Check if any existing entry has a date within the specified range
                $resultHistory = DatabaseFunction::getHistoryCountByPatientIdAndDate($database, $patientId, $entranceDate, $releaseDate);

                // Check if any similar entry exists
                if (!$resultHistory) {
                    // Rollback transaction and log error if history record cannot be added
                    $database->rollback();
                    error_log("Cannot add history record for patient ID: $patientId");
                    return null;
                } else {
                    // Insert the record if no similar entry exists
                    $patternId = DatabaseFunction::getValueFromTable($database, 'id_pattern', 'pattern', 'name_pattern', $patternName);
                    $fieldId = DatabaseFunction::getValueFromTable($database, 'id_field', 'field', 'name_field', $fieldName);
                    $doctorId = DatabaseFunction::getValueFromTable($database, 'id_doctor', 'doctor', 'last_name_doctor', $doctorName);

                    if (!$patternId || !$fieldId || !$doctorId) {
                        // Rollback transaction and log error if pattern or field or doctor ID not found
                        $database->rollback();
                        error_log("Pattern or field or doctor ID not found for pattern: $patternName, field: $fieldName, doctor: $doctorName");
                        return null;
                    }

                    // Prepare parameters for inserting new history record
                    $params = [
                        ':patient' => $patientId,
                        ':pattern' => $patternId,
                        ':field' => $fieldId,
                        ':doctor' => $doctorId,
                        ':entranceDate' => $entranceDate,
                        ':releaseDate' => $releaseDate
                    ];

                    // Insert new history record into the database
                    $resultInsert = DatabaseFunction::insertNewHistory($database, $params);

                    if ($resultInsert) {
                        // Get the last inserted history ID
                        $lastHistoryId = DatabaseFunction::getHistoryIdByPatientIdAndDate($database, $patientId, $entranceDate, $releaseDate);

                        if (!$lastHistoryId) {
                            // Rollback transaction and log error if last history ID not found
                            $database->rollback();
                            error_log("Last history ID not found for patient ID: $patientId, entrance date: $entranceDate, release date: $releaseDate");
                            return null;
                        }

                        // Prepare data for returning saved history information
                        $savedHistory = [
                            'email' => $personEmail,
                            'pattern' => $patternName,
                            'field' => $fieldName,
                            'doctor' => $doctorName,
                            'entranceDate' => $entranceDate,
                            'releaseDate' => $releaseDate,
                            'lastHistoryId' => $lastHistoryId
                        ];

                        // Update the planning table with the corresponding history ID and dates
                        if (PlanningFunction::updatePlanning($database, $lastHistoryId, $doctorId, $entranceDate, $releaseDate)) {
                            // Commit transaction if successful and return saved history data
                            $database->commit();
                            return $savedHistory;
                        } else {
                            // Rollback transaction and log error if planning update fails
                            $database->rollback();
                            error_log("Failed to update planning for history ID: $lastHistoryId");
                            return null;
                        }
                    } else {
                        // Rollback transaction and log error if inserting history record fails
                        $database->rollback();
                        error_log("Failed to insert history record for patient ID: $patientId");
                        return null;
                    }
                }
            } else {
                // Rollback transaction and log error if transaction couldn't be started
                $database->rollback();
                error_log("Failed to start transaction in saveHistory function.");
                return null;
            }
        } catch (\PDOException $e) {
            // Log PDO exceptions
            error_log('PDOException: ' . $e->getMessage());

            // Return null in case of any exception
            return null;
        }
    }
}
