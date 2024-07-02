<?php

// Namespace declaration
namespace Classes;

// Use statements to include necessary classes.
use PDO;
use PDOException;

/**
 * Class Database
 * 
 * Singleton class to manage database connections using PDO.
 */
class Database
{
    // Singleton instance
    private static $instance;

    // Database connection and credentials
    private $connection;
    private $dbHost;
    private $dbName;
    private $dbRoot;
    private $dbPassword;
    private $dbPort;

    // Flag to track transaction state
    private $inTransaction = false;

    // Static counter for open connections
    private static $openConnections = 0;

    /**
     * Constructor.
     *
     * @param string $dbHost The host of the database.
     * @param string $dbName The name of the database.
     * @param string $dbRoot The root username for the database connection.
     * @param string $dbPassword The password for the database connection.
     * @param int|null $dbPort The port number for the database connection (optional, default is null).
     */
    private function __construct($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort = null)
    {
        // Initialize database credentials
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbRoot = $dbRoot;
        $this->dbPassword = $dbPassword;
        $this->dbPort = $dbPort;
    }

    /**
     * Get an instance of the Database class.
     *
     * This method ensures that only one instance of the Database class is created (Singleton pattern).
     *
     * @param string $dbHost The host of the database.
     * @param string $dbName The name of the database.
     * @param string $dbRoot The root username for the database connection.
     * @param string $dbPassword The password for the database connection.
     * @param int|null $dbPort The port number for the database connection (optional, default is null).
     * @return Database An instance of the Database class.
     * @throws PDOException if connection fails.
     */
    public static function getInstance($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort = null)
    {
        // Create a new instance if it doesn't exist
        if (!isset(self::$instance)) {
            // Create a new Database instance with provided credentials
            self::$instance = new Database($dbHost, $dbName, $dbRoot, $dbPassword, $dbPort);

            // Attempt to connect to the database
            self::$instance->connect();
        }

        // Return the existing instance
        return self::$instance;
    }

    /**
     * Establish a database connection.
     */
    private function connect()
    {
        if ($this->connection === null) {
            // Create a PDO database connection
            try {
                if ($this->dbPort !== null) {
                    $dsn = 'mysql:host=' . $this->dbHost . ';port=' . $this->dbPort . ';dbname=' . $this->dbName;
                } else {
                    $dsn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
                }
                $this->connection = new PDO($dsn, $this->dbRoot, $this->dbPassword);
                // Set PDO attributes
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Increment the open connections counter
                self::$openConnections++;
            } catch (PDOException $e) {
                // Handle connection error
                error_log('Connection failed: ' . $e->getMessage());
                throw $e;
            }
        }
    }

    /**
     * Get database connection.
     *
     * @return PDO Database connection.
     */
    public function getConnection()
    {
        // Check if connection exists
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Disconnect from the database.
     */
    public function disconnect()
    {
        $this->connection = null;
        // Decrement the open connections counter
        self::$openConnections--;
    }

    /**
     * Get the number of open connections.
     *
     * @return int Number of open connections.
     */
    public static function getOpenConnections()
    {
        return self::$openConnections;
    }

    /**
     * Execute a SQL query without parameters.
     * 
     * @param string $query The SQL query to execute.
     * @return mixed The result of the query execution. Returns an array of rows as associative arrays on success,
     *               or false on failure.
     */
    public function executeQuery($query)
    {
        try {
            // Execute the SQL query using the database connection
            $statement = $this->getConnection()->query($query);

            // Fetch all rows from the result set as associative arrays
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error message if query execution fails
            error_log('Query failed: ' . $e->getMessage());

            // Return false to indicate query failure
            return false;
        }
    }

    /**
     * Execute a query with parameters.
     * 
     * @param string $query The SQL query to execute.
     * @param array $params The parameters for the query. Should be an associative array where keys are parameter names.
     * @param bool $fetchResult Whether to fetch any result (default true).
     * @return mixed The result of the query execution (true for INSERT or DELETE success, array for SELECT, false on failure).
     */
    public function executeQueryParam($query, $params, $fetchResult = true)
    {
        try {
            // Prepare the SQL statement
            $statement = $this->getConnection()->prepare($query);

            // Execute the statement with parameters
            $success = $statement->execute($params);

            if ($fetchResult) {
                // Fetch results for SELECT queries
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Return true for INSERT success, false otherwise
                return $success;
            }
        } catch (PDOException $e) {
            // Log query error
            error_log('Query failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Begin a transaction.
     * 
     * @return bool True if transaction started successfully, false otherwise.
     */
    public function beginTransaction()
    {
        try {
            if ($this->inTransaction) {
                // If already in transaction, return true
                return true;
            } else {
                // Start a new transaction
                $success = $this->getConnection()->beginTransaction();
                if ($success) {
                    $this->inTransaction = true;
                }
                return $success;
            }
        } catch (PDOException $e) {
            // Log transaction error
            error_log('Failed to begin transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Commit the transaction and optionally alter the specified table on success.
     * 
     * @param string|null $alterQuery The SQL query to alter the table after commit, default null.
     * @return bool True on success, false on failure.
     */
    public function commit($alterQuery = null)
    {
        try {
            if ($this->inTransaction) {
                // Attempt to commit the transaction
                $success = $this->getConnection()->commit();
                if ($success) {
                    $this->inTransaction = false;
                    if ($alterQuery !== null) {
                        // Call private method to alter the table after successful commit
                        $this->alterTable($alterQuery);
                    }
                    return true;
                } else {
                    return false; // Commit failed
                }
            } else {
                return false; // No active transaction to commit
            }
        } catch (PDOException $e) {
            // Log commit error
            error_log('Failed to commit transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rollback the transaction and alter the specified table on success.
     * 
     * @param string $alterQuery The SQL query to alter the table after rollback, default null.
     * @return bool True on success, false on failure.
     */
    public function rollback($alterQuery = null)
    {
        try {
            if ($this->inTransaction) {
                // Attempt to rollback the transaction
                $success = $this->getConnection()->rollBack();
                if ($success) {
                    $this->inTransaction = false;
                    if ($alterQuery !== null) {
                        // Call private method to alter the table after successful rollback
                        $this->alterTable($alterQuery);
                    }
                    return true;
                } else {
                    return false; // Rollback failed
                }
            } else {
                return false; // No active transaction to rollback
            }
        } catch (PDOException $e) {
            // Log rollback error
            error_log('Failed to rollback transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alter a specified table after a successful commit or rollback.
     * Set auto increment starting value to 1 for the 'id' column.
     *
     * @param string $alterQuery The SQL query to alter the table.
     * @return bool True on success, false on failure.
     */
    private function alterTable($alterQuery)
    {
        try {
            // Execute the SQL query using the database connection
            $this->getConnection()->query($alterQuery);
            return true;
        } catch (PDOException $e) {
            // Log alter table error
            error_log('Failed to alter table: ' . $e->getMessage());
            return false;
        }
    }
}
