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
use PHPFunctions\DatabaseFunction;

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
}
