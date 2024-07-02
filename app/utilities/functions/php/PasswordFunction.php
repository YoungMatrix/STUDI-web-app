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

/**
 * Class PasswordFunction
 * 
 * A class for password-related functions.
 */
class PasswordFunction
{
    /**
     * Retrieve and hash the pepper value.
     * 
     * This function retrieves the secret pepper from configuration,
     * hashes it using SHA-256, and returns the hashed value.
     * 
     * @return string The hashed pepper value.
     */
    public static function retrieveHashedPepper()
    {
        // Retrieve pepper from config php file.
        $pepper = Config::getPepper();

        // Check if the pepper is retrieved properly.
        if ($pepper === null || $pepper === '') {
            throw new \Exception("Pepper not retrieved from configuration.");
        }

        // Hashing the secret pepper.
        $hashedPepper = hash('sha256', $pepper);

        // Return hashed pepper.
        return $hashedPepper;
    }

    /**
     * Verify the hashed password against the entered password.
     *
     * This function reconstructs the hashed password using the provided salt and pepper,
     * and then compares it with the hashed password entered by user.
     *
     * @param string hashedPasswordUser The hashed password entered by the user.
     * @param string $hashedPassword The hashed password stored in the database.
     * @param string $hashedSalt The salt used for password hashing.
     * @param string $hashedPepper The hashed pepper used for password hashing.
     * @return bool Returns true if the passwords match, otherwise false.
     */
    public static function verifyPassword($hashedPasswordUser, $hashedPassword, $hashedSalt, $hashedPepper)
    {
        // Reconstruct the hashed password without salt and pepper
        $passwordNoSalt = str_replace($hashedSalt, '', $hashedPassword);
        $passwordNoSaltNoPepper = str_replace($hashedPepper, '', $passwordNoSalt);

        // Compare the reconstructed hash with the stored hashed password
        return hash_equals($hashedPasswordUser, $passwordNoSaltNoPepper);
    }

    /**
     * Generate a hashed salt value.
     *
     * This function generates a random salt, hashes it using SHA-256, and returns the hashed value.
     *
     * @return string The hashed salt value.
     */
    public static function generateHashedSalt()
    {
        $length = mt_rand(5, 10);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+[]{}|;:,.<>?';
        $salt = '';

        for ($i = 0; $i < $length; $i++) {
            $salt .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $hashedSalt = hash('sha256', $salt);

        return $hashedSalt;
    }

    /**
     * Function to create a new hashed password.
     *
     * This function hashes the password by combining it with the salt and pepper and hashing the result.
     *
     * @param string $password The password to hash.
     * @param string $hashedSalt The hashed salt value.
     * @param string $hashedPepper The hashed pepper value.
     * @return string The hashed password.
     */
    public static function hashPassword($password, $hashedSalt, $hashedPepper)
    {
        $hashedPassword = hash('sha256', $password) . $hashedSalt . $hashedPepper;
        return $hashedPassword;
    }
}
