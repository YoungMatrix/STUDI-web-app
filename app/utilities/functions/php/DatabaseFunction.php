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
use Configuration\Config;
use Classes\Database;

/**
 * Class DatabaseFunction
 * 
 * Class containing database-related functions.
 */
class DatabaseFunction
{
    /**
     * Get a specific value from a table by either ID or Name.
     *
     * This function retrieves a specific value from a table in the database based on the provided search criteria.
     *
     * @param Database $database The Database instance.
     * @param string $returnValueField The field to return (e.g., 'name_pattern', 'id_role').
     * @param string $table The table name.
     * @param string $searchField The field to search (e.g., 'id_field', 'name_role').
     * @param mixed $searchValue The value to search for.
     * @return mixed|null The value corresponding to the search value, or null if not found.
     */
    public static function getValueFromTable($database, $returnValueField, $table, $searchField, $searchValue)
    {
        try {
            // Prepare the SQL query.
            $query = "SELECT $returnValueField FROM $table WHERE $searchField = :searchValue";

            // Prepare the parameters for the query.
            $params = [':searchValue' => $searchValue];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result[0][$returnValueField];
            } else {
                return null;
            }
        } catch (\PDOException $e) {
            // Log the exception message.
            error_log('Error in getValueFromTable function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the ALTER SQL query for the doctor table from a SQL file.
     *
     * @return string|null The SQL query for altering the doctor table, or null on error.
     */
    public static function alterDoctorTableQuery()
    {
        try {
            // Define the path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/alter_doctor_table.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Return the SQL query.
            return $query;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in alterDoctorTableQuery function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves the SQL query to alter the history table.
     *
     * This function retrieves the SQL query from a file to alter the history table.
     *
     * @return string|null Returns the SQL query if successful, otherwise returns null.
     */
    public static function alterHistoryTableQuery()
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/alter_history_table.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Return the SQL query.
            return $query;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in alterHistoryTableQuery function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves the SQL query to alter the patient table.
     *
     * This function retrieves the SQL query from a file to alter the patient table.
     *
     * @return string|null Returns the SQL query if successful, otherwise returns null.
     */
    public static function alterPatientTableQuery()
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/alter_patient_table.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            return $query;
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in alterPatientTableQuery function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the ALTER SQL query for planning table from a SQL file.
     *
     * @return string|null SQL query for altering planning table, or null on error.
     */
    public static function alterPlanningTableQuery()
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/alter_planning_table.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Return the SQL query.
            return $query;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in alterPlanningTableQuery function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Function to get admin data from database by email.
     *
     * @param Database $database The Database instance.
     * @param string $email The email to search for.
     * @return array|null Returns admin data if found, otherwise null.
     */
    public static function getAdminByEmail($database, $email)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_admin_by_email.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':email' => $email];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getAdminByEmail function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the date and doctor planning details by planning ID.
     * 
     * This method reads an SQL query from a file, executes it with the provided 
     * planning ID, and returns the result.
     *
     * @param Database $database The database connection instance.
     * @param int $planningId The ID of the planning to retrieve.
     * @return array|null The result of the query if successful, or null if an error occurs.
     */
    public static function getDateDoctorFieldIdByPlanningId($database, $planningId)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_date_field_doctor_id_by_id_planning.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                // Throw an exception if the SQL file does not exist.
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':planningId' => $planningId];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the result if the query was successful and the result is not empty.
                return $result;
            } else {
                // Return null if the result is empty or false.
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getDateandDoctorPlanning function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves details of doctors from the database.
     *
     * This method reads an SQL query from a file to retrieve details such as
     * doctor ID, field ID, last name, first name, and email for all doctors,
     * ordered by doctor ID in ascending order.
     *
     * @param Database $database The Database instance to execute the query.
     * @return array|null Returns an array of doctor details if successful, or null if there's an error or no results.
     */
    public static function getDoctorDetails($database)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_doctor_details.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQuery method of the Database instance.
            $result = $database->executeQuery($query);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getDoctorDetails function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the field ID associated with a doctor ID from the database.
     * 
     * This method reads an SQL query from a file, executes it with the provided parameters,
     * and returns the field ID if found.
     *
     * @param Database $database The database connection instance.
     * @param int $otherDoctorId The ID of the other doctor to retrieve the field ID for.
     * @return int|null The field ID if found, or null if not found or an error occurs.
     */
    public static function getFieldIdByDoctorId($database, $otherDoctorId)
    {
        try {
            // Path to the SQL file containing the query.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_doctor_id_field_by_doctor_id.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                // Throw an exception if the SQL file does not exist.
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':otherDoctorId' => $otherDoctorId];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the field ID extracted from the result.
                return (int)$result[0]['id_field'];
            } else {
                // Return null if no result found.
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getFieldIdByDoctorId function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Fetches a map associating each field with a list of doctor names.
     *
     * This function retrieves the association of fields with doctors from the database.
     * It constructs a map where each field is associated with a list of doctor names.
     *
     * @param Database $database The Database instance.
     * @return array|null Returns a map associating each field with a list of doctor names if successful, otherwise returns null.
     */
    public static function getDoctorMap($database)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_doctor_last_name_by_id_field.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query.
            $result = $database->executeQuery($query);

            // Check if the query execution is successful
            if ($result !== false && !empty($result)) {
                // Initialize an empty map to store the association of fields to doctor names
                $doctorMap = [];

                // Iterate through the results and populate the map
                foreach ($result as $data) {
                    // Get the field name corresponding to the field ID
                    $fieldName = self::getValueFromTable($database, 'name_field', 'field', 'id_field', $data['id_field']);

                    // Format the doctor's name
                    $doctorLastName = $data['last_name_doctor'];

                    // Check if the field name is retrieved successfully
                    if ($fieldName) {
                        // Check if the field already exists in the map
                        if (!isset($doctorMap[$fieldName])) {
                            // If not, initialize it with an empty array
                            $doctorMap[$fieldName] = [];
                        }

                        // Add the doctor's name to the array for this field
                        $doctorMap[$fieldName][] = $doctorLastName;
                    }
                }

                // Return the map associating each field with a list of doctor names
                return $doctorMap;
            } else {
                // Return null if the query execution returns no results
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getDoctorMap function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves the list of field names from the database.
     *
     * This function fetches field names from the 'field' table in the database.
     * It then returns an array containing the list of field names.
     *
     * @param Database $database The Database instance.
     * @return array|null Returns an array of field names if successful, otherwise returns null.
     */
    public static function getFieldList($database)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_field_names.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query.
            $result = $database->executeQuery($query);

            // Check if the query execution is successful
            if ($result !== false && !empty($result)) {
                // Initialize an array to store the list of field names
                $fieldList = [];

                // Iterate through the results and extract field names
                foreach ($result as $data) {
                    // Add field name to the list
                    $fieldList[] = $data['name_field'];
                }

                // Return the list of field names
                return $fieldList;
            } else {
                // Return null if the query execution returns no results
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getFieldList function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves the count of history records by patient ID and dates.
     *
     * @param Database $database The database instance.
     * @param int $patientId The ID of the patient.
     * @param string $entranceDate The entrance date in 'Y-m-d' format.
     * @param string $releaseDate The release date in 'Y-m-d' format.
     * @return bool|null Returns true if no history records exist for the patient and dates, false if records exist, or null on error.
     */
    public static function getHistoryCountByPatientIdAndDate($database, $patientId, $entranceDate, $releaseDate)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_history_count_by_patient_id_and_date.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [
                ':patientId' => $patientId,
                ':entranceDate' => $entranceDate,
                ':releaseDate' => $releaseDate
            ];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false and !empty($result)) {
                // Check the count of history records returned.
                if ((int)$result[0]['COUNT(*)'] > 0) {
                    return false; // Records exist for the patient and dates
                } else {
                    return true; // No records exist for the patient and dates
                }
            } else {
                return false; // Error occurred or no result found
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getHistoryCountByPatientIdAndDate function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Retrieves history ID by patient ID and dates.
     *
     * @param Database $database The database instance.
     * @param int $patientId The ID of the patient.
     * @param string $entranceDate The entrance date in 'Y-m-d' format.
     * @param string $releaseDate The release date in 'Y-m-d' format.
     * @return int|null The ID of the history record, or null if not found or on error.
     */
    public static function getHistoryIdByPatientIdAndDate($database, $patientId, $entranceDate, $releaseDate)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_history_id_by_patient_id_and_date.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [
                ':patientId' => $patientId,
                ':entranceDate' => $entranceDate,
                ':releaseDate' => $releaseDate
            ];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the ID of the history record found.
                return (int)$result[0]['id_history'];
            } else {
                // Return null if no history record is found.
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getHistoryIdByPatientIdAndDate function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the maximum doctor ID from the database.
     *
     * This function retrieves the maximum doctor ID from the database.
     *
     * @param Database $database The Database instance used for querying.
     *
     * @return int|false The maximum doctor ID if found, or false if no records found or an error occurred.
     */
    public static function getMaxDoctorId($database)
    {
        try {
            // Path to the SQL file containing the query.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_max_doctor_id.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQuery method of the Database instance.
            $result = $database->executeQuery($query);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the maximum doctor ID.
                return (int) $result[0]['MAX(id_doctor)'];
            } else {
                // Return false if no result found.
                return false;
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getMaxDoctorId function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Get the ID of a new doctor using provided parameters.
     *
     * This function retrieves the maximum ID of a doctor from the 'doctor' table based on the provided details.
     *
     * @param Database $database The Database instance.
     * @param array $params The parameters for the SQL query.
     * @return int|false The doctor ID, or false if not found or an error occurs.
     */
    public static function getNewDoctorId($database, $params)
    {
        try {
            // Path to the SQL file containing the query.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_new_doctor_id_by_its_details.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the doctor ID.
                return (int) $result[0]['id_doctor'];
            } else {
                // Return false if no result found.
                return false;
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getNewDoctorId function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Get patient data by email.
     *
     * This function retrieves all columns from the 'patient' table for a specific email.
     *
     * @param Database $database The Database instance.
     * @param string $email The email of the patient.
     * @return array|null The patient data, or null if not found.
     */
    public static function getPatientDataByEmail($database, $email)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_patient_by_email.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':email' => $email];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getPatientDataByEmail function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves patient count by email.
     *
     * This function retrieves the count of patients with the given email from the database.
     *
     * @param Database $database The Database instance.
     * @param string $email The email to search for.
     * @return bool Returns false if a patient with the email exists, true otherwise, or false on error.
     */
    public static function getPatientCountByEmail($database, $email)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_patient_count_by_email.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':email' => $email];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false and !empty($result)) {
                if ((int)$result[0]['COUNT(*)'] > 0) {
                    return false; // Patient with email exists
                } else {
                    return true; // No patient with email exists
                }
            } else {
                return false; // Error occurred
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getPatientCountByEmail function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Get history records for a specific patient.
     *
     * This function retrieves history records for a patient from the database using a query from a .sql file.
     *
     * @param Database $database The Database instance.
     * @param int $patientId The ID of the patient.
     * @return array|null The history records, or null if an error occurs.
     */
    public static function getHistoryForPatient($database, $patientId)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_patient_history.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':patientId' => $patientId];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getHistoryForPatient function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieves the list of pattern names from the database.
     *
     * This function fetches pattern names from the 'pattern' table in the database.
     * It then returns an array containing the list of pattern names.
     *
     * @param Database $database The Database instance.
     * @return array|null Returns an array of pattern names if successful, otherwise returns null.
     */
    public static function getPatternList($database)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_pattern_names.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query.
            $result = $database->executeQuery($query);

            // Check if the query execution is successful
            if ($result !== false && !empty($result)) {
                // Initialize an array to store the list of pattern names
                $patternList = [];

                // Iterate through the results and extract pattern names
                foreach ($result as $data) {
                    // Add pattern name to the list
                    $patternList[] = $data['name_pattern'];
                }

                // Return the list of pattern names
                return $patternList;
            } else {
                // Return null if the query execution returns no results
                return null;
            }
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in getPatternList function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Retrieve the count of planning records for a specific date and doctor ID from the database.
     * 
     * This method reads an SQL query from a file, executes it with the provided parameters,
     * and returns the count of planning records.
     *
     * @param Database $database The database connection instance.
     * @param string $currentPlanningDte The date of the planning to check.
     * @param int $otherDoctorId The ID of the other doctor to check planning for.
     * @return int|false The count of planning records if found, or false if no records exist or an error occurs.
     */
    public static function getPlanningCountByDateAndDoctorId($database, $currentPlanningDte, $otherDoctorId)
    {
        try {
            // Path to the SQL file containing the query.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_planning_count_by_date_and_doctor_id.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                // Throw an exception if the SQL file does not exist.
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [
                ':planningDate' => $currentPlanningDte,
                ':otherDoctorId' => $otherDoctorId
            ];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                // Return the count of planning records.
                return (int) $result[0]['COUNT(*)'];
            } else {
                // Return false if no result found.
                return false;
            }
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in getPlanningCountByDateAndDoctorId function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Retrieve planning and history details from the database.
     *
     * This function retrieves planning and history details based on a SQL query stored in an external file.
     * It executes the query using the Database instance provided and returns the result as an array.
     *
     * @param Database $database The Database instance used for querying.
     * @param string $today The date parameter to filter the planning details.
     *
     * @return array|null Returns an array containing planning and history details, or null if no records found or an error occurred.
     */
    public static function getPlanningDetails($database, $today)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/get_planning_details.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Prepare the parameters for the query.
            $params = [':today' => $today];

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params);

            // Check if a result is found.
            if ($result !== false && !empty($result)) {
                return $result; // Return the result array if not empty
            } else {
                return null; // Return null if no records found
            }
        } catch (\Exception $e) {
            // Log the exception message to error log.
            error_log('Error in getPlanningDetails function: ' . $e->getMessage());

            // Return null if an exception is caught.
            return null;
        }
    }

    /**
     * Inserts a new doctor record into the database.
     *
     * @param Database $database The Database instance.
     * @param array $params The parameters for the SQL query.
     * @return bool Returns true on success, false on failure.
     */
    public static function insertNewDoctor($database, $params)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/insert_new_doctor.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params, false);

            // Return result.
            return $result;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in insertNewDoctor function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Inserts a new history record into the database.
     *
     * @param Database $database The database instance.
     * @param array $params The parameters for the SQL query.
     * @return bool Returns true on success, false on failure.
     */
    public static function insertNewHistory($database, $params)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/insert_new_history.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params, false);

            // Return result.
            return $result;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in insertNewHistory function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Inserts a new patient into the database.
     *
     * This function inserts a new patient record into the database using a prepared SQL query.
     *
     * @param Database $database The Database instance.
     * @param array $params The parameters for the SQL query.
     * @return bool Returns true if the insertion is successful, false otherwise.
     */
    public static function insertNewPatient($database, $params)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/insert_new_patient.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params, false);

            // Return result.
            return $result;
        } catch (\Exception $e) {
            // Log the exception message.
            error_log('Error in insertNewPatient function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Inserts a new planning record into the database.
     *
     * @param Database $database The database instance.
     * @param array $params The parameters for the SQL query.
     * @return bool Returns true on success, false on failure.
     */
    public static function insertNewPlanning($database, $params)
    {
        try {
            // Path to the SQL file.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/insert_new_planning.sql';

            // Read the SQL query from the file.
            if (!file_exists($sqlFilePath)) {
                throw new \Exception("SQL file not found: $sqlFilePath");
            }
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params, false);

            // Return result.
            return $result;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in insertNewPlanning function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }

    /**
     * Update doctor planning information in the database using a specified SQL query.
     * 
     * This method reads an SQL query from a file, executes it with the provided parameters,
     * and updates the doctor planning information.
     *
     * @param Database $database The database connection instance.
     * @param array $params Parameters to be used in the SQL query.
     * @return bool True on success, false on failure or error.
     */
    public static function updatePlanning($database, $params)
    {
        try {
            // Path to the SQL file containing the query.
            $sqlFilePath = Config::getRootPath() . '/app/assets/sql/update_doctor_planning_by_id_planning.sql';

            // Check if the SQL file exists.
            if (!file_exists($sqlFilePath)) {
                // Throw an exception if the SQL file does not exist.
                throw new \Exception("SQL file not found: $sqlFilePath");
            }

            // Read the SQL query from the file.
            $query = file_get_contents($sqlFilePath);

            // Execute the query using the executeQueryParam method of the Database instance.
            $result = $database->executeQueryParam($query, $params, false);

            // Return the result of the query execution.
            return $result;
        } catch (\Exception $e) {
            // Log the exception message to error_log.
            error_log('Error in updatePlanning function: ' . $e->getMessage());

            // Return false if an exception is caught.
            return false;
        }
    }
}
