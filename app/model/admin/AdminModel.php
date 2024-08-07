<?php

// File verified

// Namespace declaration
namespace AdminModel;

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use PHPFunctions\DoctorFunction;
use PHPFunctions\PasswordFunction;
use PHPFunctions\PlanningFunction;
use PHPFunctions\VerificationFunction;

// Initialize the configuration.
Config::init();

/**
 * Class AdminModel
 * 
 * Model class responsible for handling administrative operations related to doctors.
 */
class AdminModel
{
    /**
     * Create a new doctor based on sanitized POST data and store it in session if successful.
     */
    public static function createDoctor()
    {
        // Sanitize input data for new doctor's last name, first name, and field
        $safeNewDoctorLastName = htmlspecialchars($_POST['newDoctorLastName'], ENT_QUOTES, 'UTF-8');
        $safeNewDoctorFirstName = htmlspecialchars($_POST['newDoctorFirstName'], ENT_QUOTES, 'UTF-8');
        $safeField = htmlspecialchars($_POST['field'], ENT_QUOTES, 'UTF-8');

        // Validate if last name and first name contain only alphabetic characters
        $successLastName = VerificationFunction::areAllCharactersAlpha($safeNewDoctorLastName);
        $successFirstName = VerificationFunction::areAllCharactersAlpha($safeNewDoctorFirstName);

        // Generate salt for password hashing
        $hashedSalt = PasswordFunction::generateHashedSalt();

        // Retrieve hashed pepper from configuration
        $hashedPepper = PasswordFunction::retrieveHashedPepper();

        // Proceed if last name and first name are valid
        if ($successLastName && $successFirstName) {
            // Create a new doctor record in the database
            $doctor = DoctorFunction::createDoctor(Config::getDatabase(), $safeNewDoctorLastName, $safeNewDoctorFirstName, $safeField, $hashedSalt, $hashedPepper);

            // If doctor creation was successful, store the doctor object in the session
            if ($doctor !== null) {
                // Store the newly created doctor object in the session
                Config::setDoctor($doctor);

                // Retrieve and store updated list of doctors in the session
                Config::setDoctorRecords(DoctorFunction::retrieveDoctor(Config::getDatabase()));
            }
        } else {
            // Log an error if last name or first name are not valid
            error_log('Variables (name and firstname) not valid.');
        }
    }

    /**
     * Method to change the planning for a doctor.
     *
     * This method sanitizes the input data, changes the planning using 
     * PlanningFunction, and updates the session with the new planning records.
     */
    public static function changePlanning()
    {
        // Sanitize input data to prevent XSS attacks
        $safePlanningId = htmlspecialchars($_POST['planningId'], ENT_QUOTES, 'UTF-8');
        $safeOtherDoctorId = htmlspecialchars($_POST['otherDoctorId'], ENT_QUOTES, 'UTF-8');

        // Change the planning using the sanitized input data
        $planningChanged = PlanningFunction::changePlanning(Config::getDatabase(), $safePlanningId, $safeOtherDoctorId);

        // Check if the planning change was successful
        if ($planningChanged) {
            // Retrieve and store the updated list of planning records in the session
            Config::setPlanningRecords(PlanningFunction::retrievePlanning(Config::getDatabase()));
        } else {
            // Log an error message if the planning change failed
            error_log('Planning could not be changed.');
        }
    }
}
