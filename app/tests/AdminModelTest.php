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
use PHPFunctions\DatabaseFunction;
use PHPFunctions\DoctorFunction;
use PHPFunctions\PlanningFunction;

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
     * Holds the matricule of the first test doctor.
     *
     * @var string $testDoctorMatricule1
     */
    private static $testDoctorMatricule1;

    /**
     * Holds the email of the first test doctor.
     *
     * @var string $testDoctorEmail1
     */
    private static $testDoctorEmail1;

    /**
     * Holds the matricule of the second test doctor.
     *
     * @var string $testDoctorMatricule2
     */
    private static $testDoctorMatricule2;

    /**
     * Holds the email of the second test doctor.
     *
     * @var string $testDoctorEmail2
     */
    private static $testDoctorEmail2;

    /**
     * Test method for creating two doctors.
     */
    public static function testCreateDoctor()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Call createDoctor with test values
            $result1 = DoctorFunction::createDoctor(
                $database,
                'SMITH',
                'John',
                'Médecine Générale',
                'hashedSalt',
                'hashedPepper'
            );

            // Call createDoctor with test values
            $result2 = DoctorFunction::createDoctor(
                $database,
                'JACK',
                'Doe',
                'Médecine Générale',
                'hashedSalt',
                'hashedPepper'
            );

            // Check the result
            if ($result1 instanceof Doctor) {
                echo "PASS: First doctor created successfully.<br>";
                echo "Doctor Matricule: " . $result1->getMatricule() . "<br>";
                echo "Doctor Name: " . $result1->getFirstName() . " " . $result1->getLastName() . "<br>";
                echo "Doctor Email: " . $result1->getEmail() . "<br>";
                echo "Doctor Field: " . $result1->getField() . "<br>";
            } else {
                echo "Failed to create the first doctor.<br>";
            }

            // Check the result
            if ($result2 instanceof Doctor) {
                echo "PASS: Second doctor created successfully.<br>";
                echo "Doctor Matricule: " . $result2->getMatricule() . "<br>";
                echo "Doctor Name: " . $result2->getFirstName() . " " . $result2->getLastName() . "<br>";
                echo "Doctor Email: " . $result2->getEmail() . "<br>";
                echo "Doctor Field: " . $result2->getField() . "<br>";
            } else {
                echo "Failed to create the second doctor.<br>";
            }

            // Sample data for test
            self::$testDoctorMatricule1 = $result1->getMatricule();
            self::$testDoctorEmail1 = $result1->getEmail();
            self::$testDoctorMatricule2 = $result2->getMatricule();
            self::$testDoctorEmail2 = $result2->getEmail();
        } catch (\Exception $e) {
            // Handle any exceptions thrown during testing
            echo "Error during testCreateDoctor: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Test method for changing doctor planning.
     */
    public static function testChangePlanning()
    {
        try {
            // Obtain database connection
            $database = Config::getDatabase();

            // Begin transaction
            $database->beginTransaction();

            // Sample data for test
            $historyData = [
                'id_patient' => 1,
                'id_pattern' => 1,
                'id_field' => 5,
                'id_doctor' => self::$testDoctorMatricule1,
                'date_entrance' => date('Y-m-d'),
                'date_release' => date('Y-m-d', strtotime('+1 day')), // Date release set to tomorrow
            ];

            // Insert history entry
            $insertQuery = "INSERT INTO history (id_patient, id_pattern, id_field, id_doctor, date_entrance, date_release) 
                        VALUES (:id_patient, :id_pattern, :id_field, :id_doctor, :date_entrance, :date_release)";
            $insertParams = [
                ':id_patient' => $historyData['id_patient'],
                ':id_pattern' => $historyData['id_pattern'],
                ':id_field' => $historyData['id_field'],
                ':id_doctor' => $historyData['id_doctor'],
                ':date_entrance' => $historyData['date_entrance'],
                ':date_release' => $historyData['date_release'],
            ];
            $insertSuccess = $database->executeQueryParam($insertQuery, $insertParams, false);

            if (!$insertSuccess) {
                $database->rollback();
                echo "Failed to insert history.<br>";
                return;
            }

            // Select the newly inserted history ID
            $selectQuery = "SELECT id_history FROM history WHERE id_patient = :id_patient AND id_doctor = :id_doctor AND date_entrance = :date_entrance";
            $selectParams = [
                ':id_patient' => $historyData['id_patient'],
                ':id_doctor' => $historyData['id_doctor'],
                ':date_entrance' => $historyData['date_entrance'],
            ];
            $result = $database->executeQueryParam($selectQuery, $selectParams);

            if (!$result) {
                $database->rollback();
                echo "Failed to retrieve history ID.<br>";
                return;
            }

            // Extract history ID from result
            $historyId = $result[0]['id_history'];

            // Sample data for test
            $planningData = [
                'id_history' => $historyId,
                'id_confirmed_doctor' => self::$testDoctorMatricule1,
                'date_planning' => date('Y-m-d'),
            ];

            // Insert planning entry
            $insertQuery = "INSERT INTO planning (id_history, id_confirmed_doctor, date_planning) 
                        VALUES (:id_history, :id_confirmed_doctor, :date_planning)";
            $insertParams = [
                ':id_history' => $planningData['id_history'],
                ':id_confirmed_doctor' => $planningData['id_confirmed_doctor'],
                ':date_planning' => $planningData['date_planning'],
            ];
            $insertSuccess = $database->executeQueryParam($insertQuery, $insertParams, false);

            if (!$insertSuccess) {
                $database->rollback();
                echo "Failed to insert planning.<br>";
                return;
            }

            // Select the newly inserted planning ID
            $selectQuery = "SELECT id_planning FROM planning WHERE id_history = :id_history AND id_confirmed_doctor = :id_confirmed_doctor AND date_planning = :date_planning";
            $selectParams = [
                ':id_history' => $planningData['id_history'],
                ':id_confirmed_doctor' => $planningData['id_confirmed_doctor'],
                ':date_planning' => $planningData['date_planning'],
            ];
            $result = $database->executeQueryParam($selectQuery, $selectParams);

            if (!$result) {
                $database->rollback();
                echo "Failed to retrieve planning ID.<br>";
                return;
            }

            // Extract planning ID from result
            $planningId = $result[0]['id_planning'];

            // Sample data for test
            $otherDoctorId = self::$testDoctorMatricule2; // Replace with another doctor ID

            // Call changePlanning method
            $result = PlanningFunction::changePlanning($database, $planningId, $otherDoctorId);

            // Check the result
            if ($result) {
                echo "PASS: Planning changed successfully.<br>";
            } else {
                echo "Failed to change planning.<br>";
            }

            // Delete history entry and reset auto increment
            self::deleteHistoryAndResetAutoIncrement($database, $historyId);

            // Delete planning entry and reset auto increment
            self::deletePlanningAndResetAutoIncrement($database, $planningId);

            // Commit transaction
            $database->commit();
        } catch (\Exception $e) {
            // Rollback transaction and log error if deletion fails
            $database->rollback();
            echo "Error during testChangePlanning: " . $e->getMessage() . "<br>";
        } finally {
            // Delete doctors and reset auto increment regardless of success or failure
            self::deleteDoctorAndResetAutoIncrement($database, self::$testDoctorEmail1);
            self::deleteDoctorAndResetAutoIncrement($database, self::$testDoctorEmail2);
        }
    }

    /**
     * Delete a history entry from the database and reset auto increment.
     *
     * @param Database $database Database connection object.
     * @param int $historyIdToDelete The ID of the history entry to delete.
     */
    private static function deleteHistoryAndResetAutoIncrement($database, $historyIdToDelete)
    {
        try {
            // Begin transaction
            $database->beginTransaction();

            // Delete the history entry from the database
            $deleteQuery = "DELETE FROM history WHERE id_history = :id";
            $deleteParams = [':id' => $historyIdToDelete];
            $deleteSuccess = $database->executeQueryParam($deleteQuery, $deleteParams, false);

            // Reset auto increment for planning table
            $alterQuery = DatabaseFunction::alterHistoryTableQuery();

            // Reset auto increment for history table if deletion was successful
            if ($deleteSuccess) {
                // Commit transaction
                $database->commit($alterQuery);
                echo "PASS: Deleted history successfully with id: $historyIdToDelete. <br>";
                echo "PASS: Reset auto increment for history table successfully.<br>";
            } else {
                echo "Failed to delete history entry.<br>";
            }
        } catch (\Exception $e) {
            // Rollback transaction and log error if deletion fails
            $database->rollback();
            echo "Error deleting history entry: " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Delete a planning entry from the database and reset auto increment.
     *
     * @param Database $database Database connection object.
     * @param int $planningIdToDelete The ID of the planning entry to delete.
     */
    private static function deletePlanningAndResetAutoIncrement($database, $planningIdToDelete)
    {
        try {
            // Begin transaction
            $database->beginTransaction();

            // Delete the planning entry from the database
            $deleteQuery = "DELETE FROM planning WHERE id_planning = :id";
            $deleteParams = [':id' => $planningIdToDelete];
            $deleteSuccess = $database->executeQueryParam($deleteQuery, $deleteParams, false);

            // Reset auto increment for planning table
            $alterQuery = DatabaseFunction::alterPlanningTableQuery();

            if ($deleteSuccess) {
                // Commit transaction
                $database->commit($alterQuery);
                echo "PASS: Deleted planning successfully with id: $planningIdToDelete. <br>";
                echo "PASS: Reset auto increment for planning table successfully.<br>";
            } else {
                echo "Failed to delete planning.<br>";
            }
        } catch (\Exception $e) {
            // Rollback transaction and log error if deletion fails
            $database->rollback();
            echo "Error deleting planning: " . $e->getMessage() . "<br>";
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
            $alterQuery = DatabaseFunction::alterDoctorTableQuery();

            if ($deleteSuccess) {
                // Commit transaction
                $database->commit($alterQuery);
                echo "PASS: Deleted doctor successfully with email: $emailToDelete. <br>";
                echo "PASS: Reset auto increment for doctor table successfully.<br>";
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

        echo "<h1>Running testChangePlanning...</h1>";
        self::testChangePlanning();

        echo "<h1>End of 'AdminModelTest.php'.</h1>";
    }
}
