<?php

// File verified

// Namespace declaration
namespace Test;

// Use statements to include necessary classes.
use Classes\Database;

/**
 * Class DatabaseTest
 * 
 * This class contains methods to test various functionalities of the Database class,
 * including testing the singleton behavior, committing transactions, and rolling back transactions.
 */
class DatabaseTest
{
    /**
     * Function to demonstrate singleton behavior with multiple instances.
     *
     * @param string $dbHost The host name for the database connection.
     * @param string $dbRoot The username (root) for the database connection.
     * @param string $dbName The name of the database to connect to.
     * @param string $dbPassword The password for the database user.
     * @param string|null $dbPort The port number for the database connection (can be null).
     * @param int $numInstances Number of instances to create.
     */
    private function testSingleton($dbHost, $dbRoot, $dbName, $dbPassword, $dbPort = null, $numInstances = 3)
    {
        echo "<h1>testSingleton:</h1>";
        echo "Attempting to create $numInstances instances...<br>";

        $instances = [];

        for ($i = 1; $i <= $numInstances; $i++) {
            $db = Database::getInstance($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort);
            $instances[] = $db;
        }

        $sameInstance = true;
        $firstInstance = $instances[0];
        foreach ($instances as $instance) {
            if ($instance !== $firstInstance) {
                $sameInstance = false;
                break;
            }
        }

        echo "Number of instances created: $numInstances<br>";
        if ($sameInstance) {
            echo "All instances refer to the same object (singleton pattern).<br>";
            echo "<h1>testSingleton succeeded.</h1>";
        } else {
            echo "Instances do not refer to the same object.<br>";
        }
    }

    /**
     * Function to test transaction commit scenario.
     * 
     * @param Database $db The Database instance.
     */
    private function testCommit($db)
    {
        echo "<h1>testCommit:</h1>";
        // Check if tables exist, create if not (for testing purpose)
        $createTables = "
            CREATE TABLE IF NOT EXISTS tests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                age INT NOT NULL
            );
            ";

        // Execute query
        $db->executeQuery($createTables);

        // Begin transaction
        $transactionStarted = $db->beginTransaction();

        if ($transactionStarted) {
            // Insert into 'tests' table
            $insertQuery1 = "INSERT INTO tests (name, age) VALUES (:name, :age)";
            $params1 = ['name' => 'John Doe', 'age' => 30];
            $insertSuccess1 = $db->executeQueryParam($insertQuery1, $params1, false);

            if ($insertSuccess1) {
                // Commit transaction if both insertions were successful
                $commit = $db->commit();
                if ($commit) {
                    echo "Transaction committed successfully.<br>";

                    // Fetch and display tests from 'tests' table
                    $selectQuery = "SELECT id, name, age FROM tests";
                    $tests = $db->executeQuery($selectQuery);

                    if ($tests !== false && !empty($tests)) {
                        echo "<h2>tests</h2>";
                        echo "<ul>";
                        foreach ($tests as $test) {
                            echo "<li>ID: {$test['id']}, Name: {$test['name']}, Age: {$test['age']}</li>";
                        }
                        echo "</ul>";
                        echo "<h1>testCommit succeeded.</h1>";
                    } else {
                        echo "Failed to fetch tests.<br>";
                    }
                } else {
                    echo "Commit failed.<br>";
                }
            } else {
                // Rollback transaction if any insertion failed
                $rollback = $db->rollback();
                if ($rollback) {
                    echo "Rollback performed due to failed insertion.<br>";
                } else {
                    echo "Rollback failed.<br>";
                }
            }
        } else {
            echo "Failed to start transaction.<br>";
        }
    }

    /**
     * Function to test transaction rollback scenario.
     * 
     * @param Database $db The Database instance.
     */
    private function testRollback($db)
    {
        echo "<h1>testRollback:</h1>";
        // Check if tables exist, create if not (for testing purpose)
        $createTables = "
            CREATE TABLE IF NOT EXISTS tests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                age INT NOT NULL
            );
            ";

        $db->executeQuery($createTables);

        // Begin transaction
        $transactionStarted = $db->beginTransaction();

        if ($transactionStarted) {
            // Insert into 'tests' table
            $insertQuery1 = "INSERT INTO tests (name, age) VALUES (:name, :age)";
            $params1 = ['name' => 'John Doe', 'age' => 30];
            $insertSuccess1 = $db->executeQueryParam($insertQuery1, $params1, false);

            // Insert into 'tests1' table (simulate failure)
            $insertQuery2 = "INSERT INTO tests1 (name, age) VALUES (:name, :age)";
            $params2 = ['name' => 'Jane Doe', 'age' => 25];
            $insertSuccess2 = $db->executeQueryParam($insertQuery2, $params2, false);

            if ($insertSuccess1 && $insertSuccess2) {
                // Commit transaction if both insertions were successful
                $commit = $db->commit();
                if ($commit) {
                    echo "Transaction committed successfully.<br>";

                    // Fetch and display tests from 'tests' table
                    $selectQuery = "SELECT id, name, age FROM tests";
                    $tests = $db->executeQuery($selectQuery);

                    if ($tests !== false && !empty($tests)) {
                        echo "<h2>tests</h2>";
                        echo "<ul>";
                        foreach ($tests as $test) {
                            echo "<li>ID: {$test['id']}, Name: {$test['name']}, Age: {$test['age']}</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "Failed to fetch tests.<br>";
                    }
                } else {
                    echo "Commit failed.<br>";
                }
            } else {
                // Rollback transaction if any insertion failed
                $rollback = $db->rollback();
                if ($rollback) {
                    echo "Rollback performed due to failed insertion.<br>";

                    // Fetch and display tests from 'tests' table after rollback
                    $selectQuery = "SELECT id, name, age FROM tests";
                    $tests = $db->executeQuery($selectQuery);

                    if ($tests !== false && !empty($tests)) {
                        echo "<h2>tests (after rollback)</h2>";
                        echo "<ul>";
                        foreach ($tests as $test) {
                            echo "<li>ID: {$test['id']}, Name: {$test['name']}, Age: {$test['age']}</li>";
                        }
                        echo "</ul>";
                        echo "<h1>testRollback succeeded.</h1>";
                    } else {
                        echo "Failed to fetch tests after rollback.<br>";
                    }
                } else {
                    echo "Rollback failed.<br>";
                }
            }
        } else {
            echo "Failed to start transaction.<br>";
        }
    }

    /**
     * Function to demonstrate various database operations.
     *
     * @param string $dbHost The host name for the database connection.
     * @param string $dbRoot The testname (root) for the database connection.
     * @param string $dbName The name of the database to connect to.
     * @param string $dbPassword The password for the database test.
     * @param string|null $dbPort The port number for the database connection (can be null).
     */
    public static function testDatabaseOperations($dbHost, $dbRoot, $dbName, $dbPassword, $dbPort)
    {
        try {
            // Create an instance of the DatabaseTest class to call the non-static methods
            $databaseTest = new self();

            // Call the function to test singleton behavior with 3 instances
            $databaseTest->testSingleton($dbHost, $dbRoot, $dbName, $dbPassword, $dbPort);

            echo "---------------------------<br>";

            // Get the instance of the Database class with $dbPort = null
            $db = Database::getInstance($dbHost, $dbName, $dbRoot, $dbPassword, null);

            // Call the function to test commit scenario
            $databaseTest->testCommit($db);

            echo "---------------------------<br>";

            // Call the function to test rollback scenario
            $databaseTest->testRollback($db);

            echo "---------------------------<br>";

            echo "Number of open connections before disconnect: " . $db->getOpenConnections() . "<br>";

            echo "Disconnecting...<br>";

            $db->disconnect();

            echo "Number of open connections after disconnect: " . $db->getOpenConnections() . "<br>";

            echo "---------------------------";

            if ($db->getOpenConnections() === 0) {
                echo "<h1>End of 'DatabaseTest.php'.</h1>";
                echo "---------------------------<br>";
            } else {
                echo "Connection is not closed. Please check.";
            }
        } catch (\Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
