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
use Classes\Planning;

// DateTime importation
use DateTime;
use DateInterval;

/**
 * Class PlanningFunction
 *
 * This class provides functionality related to updating the planning table
 * with history records.
 */
class PlanningFunction
{
    /**
     * Change the doctor assigned to a specific planning entry.
     *
     * @param Database $database The Database instance.
     * @param int $planningId The ID of the planning entry.
     * @param string $otherDoctorId The ID of the new doctor.
     * @return bool True if the doctor is successfully changed, false otherwise.
     */
    public static function changePlanning($database, $planningId, $otherDoctorId)
    {
        try {
            // Check and alter the planning table structure if necessary
            $alterQuery = DatabaseFunction::alterPlanningTableQuery();
            if ($alterQuery) {
                $database->executeQuery($alterQuery);
            }

            // Begin transaction
            if (!$database->beginTransaction()) {
                error_log("Failed to start transaction in changePlanning function.");
                return false;
            }

            // Retrieve current planning details
            $result = DatabaseFunction::getDateDoctorFieldIdByPlanningId($database, $planningId);

            if (!$result) {
                $database->rollback();
                error_log("Error: Last doctor ID not found in changePlanning function.");
                return false; // Return false if query fails or no result
            }

            // Extract current planning details
            $currentPlanningDate = $result[0]['date_planning'];
            $currentDoctorId = $result[0]['id_confirmed_doctor'];
            $currentDoctorField = $result[0]['id_field'];

            // Retrieve the field ID for the new doctor
            $otherDoctorField = DatabaseFunction::getFieldIdByDoctorId($database, $otherDoctorId);

            if (!$otherDoctorField) {
                $database->rollback();
                error_log("Error: New doctor ID not found in changePlanning function.");
                return false; // Return false if query fails or no result
            }

            // Check if the new doctor is the same as the current one or if they belong to different fields
            if ($currentDoctorId == $otherDoctorId || $currentDoctorField != $otherDoctorField) {
                $database->rollback();
                error_log("Error: New doctor ID is the same as the current one or belongs to a different field in changePlanning function.");
                return false; // Return false if the new doctor is the same as the current one or if they belong to different fields
            }

            // Check the count of planning records for the new doctor and date
            $count = DatabaseFunction::getPlanningCountByDateAndDoctorId($database, $currentPlanningDate, $otherDoctorId);

            if ($count === false || $count >= 5) {
                $database->rollback();
                error_log("Error: Planning count validation failed in changePlanning function.");
                return false; // Return false if query fails or no result
            }

            // Prepare update parameters and execute the update query
            $params = [
                ':newConfirmedDoctorId' => $otherDoctorId,
                ':planningId' => $planningId
            ];
            $success = DatabaseFunction::updatePlanning($database, $params);

            // Commit transaction if update was successful, rollback otherwise
            if ($success !== false) {
                $database->commit();
                return true; // Return true if the update was successful
            } else {
                $database->rollback();
                error_log("Error: Update planning query failed in changePlanning function.");
                return false; // Return false if the update query fails
            }
        } catch (\PDOException $e) {
            // Log database exception message
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update planning table with history records.
     *
     * This function updates the planning table with the specified history ID and dates
     * between the entrance date and release date.
     *
     * @param Database $database The Database instance.
     * @param int $lastHistory The ID of the last history record inserted.
     * @param int $doctorId The ID of the confirmed doctor.
     * @param string $entranceDate The entrance date in YYYY-MM-DD format.
     * @param string $releaseDate The release date in YYYY-MM-DD format.
     *
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public static function updatePlanning($database, $lastHistory, $doctorId, $entranceDate, $releaseDate)
    {
        try {
            // Initialize variables for date iteration
            $currentDate = new DateTime($entranceDate);
            $releaseDateTime = new DateTime($releaseDate);
            $interval = new DateInterval('P1D'); // 1 day interval

            // Iterate through each date between the entrance and release dates
            while ($currentDate <= $releaseDateTime) {
                // Format the date as YYYY-MM-DD
                $formattedDate = $currentDate->format('Y-m-d');

                // Parameters for the query
                $params = [
                    ':historyId' => $lastHistory,
                    ':confirmedDoctorId' => $doctorId,
                    ':date' => $formattedDate
                ];

                // Insert into planning table
                $resultInsert = DatabaseFunction::insertNewPlanning($database, $params);

                if (!$resultInsert) {
                    // Return false if insertion fails
                    return false;
                }

                // Move to the next date
                $currentDate->add($interval);
            }

            // Return true if all insertions were successful
            return true;
        } catch (\PDOException $e) {
            // Log PDO exceptions
            error_log('PDOException: ' . $e->getMessage());

            // Return false in case of any exception
            return false;
        }
    }

    /**
     * Retrieve planning data from the database for dates greater than or equal to today.
     *
     * This function retrieves planning data from the database for dates greater than or equal to today.
     * It includes patient and doctor information associated with each planning record.
     *
     * @param Database $database The Database instance used for querying.
     *
     * @return array|null An array of Planning objects representing the schedule, or null if no records found.
     */
    public static function retrievePlanning($database)
    {
        // Initialize an empty array to store the schedule
        $planningRecords = [];

        // Get today's date
        $today = date("Y-m-d");

        try {
            // Retrieve planning details from the database based on today's date
            $planningData = DatabaseFunction::getPlanningDetails($database, $today);

            // Check if records are found
            if (!$planningData) {
                return $planningRecords; // Return empty array if no records found
            }

            // Process each planning record
            foreach ($planningData as $data) {
                // Retrieve patient and doctor names using DatabaseFunction::getValueFromTable
                $patientName = DatabaseFunction::getValueFromTable($database, 'last_name_patient', 'patient', 'id_patient', $data['id_patient']);
                $patternName = DatabaseFunction::getValueFromTable($database, 'name_pattern', 'pattern', 'id_pattern', $data['id_pattern']);
                $fieldName = DatabaseFunction::getValueFromTable($database, 'name_field', 'field', 'id_field', $data['id_field']);
                $doctorName = DatabaseFunction::getValueFromTable($database, 'last_name_doctor', 'doctor', 'id_doctor', $data['id_confirmed_doctor']);

                // Check if any required data is missing
                if (!$patientName || !$patternName || !$fieldName || !$doctorName) {
                    return $planningRecords; // Return empty array if any required data is missing
                }

                // Create a new Planning object and add it to the array of planning records
                $planningRecord = new Planning(
                    $data['id_planning'],
                    $patientName,
                    $patternName,
                    $fieldName,
                    $data['id_field'],
                    $doctorName,
                    $data['id_confirmed_doctor'],
                    $data['date_entrance'],
                    $data['date_release'],
                    $data['date_planning']
                );

                $planningRecords[] = $planningRecord;
            }

            // Return the array of Planning objects representing the schedule
            return $planningRecords;
        } catch (\Exception $e) {
            // Log any exceptions that occur during data retrieval
            error_log('Error in retrievePlanning function: ' . $e->getMessage());

            // Return empty list if an exception occurs
            return [];
        }
    }
}
