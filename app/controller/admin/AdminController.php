<?php

// File verified

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use AdminModel\AdminModel;
use PHPFunctions\FileFunction;

// Initialize the configuration.
Config::init();

// Resetting the new doctor success flag to false.
Config::setNewDoctorSuccess(false);

// Resetting the new doctor error flag to false.
Config::setNewDoctorError(false);

// Resetting the change planning error flag to false.
Config::setChangePlanningError(false);

// Resetting the change planning success flag to false.
Config::setChangePlanningSuccess(false);

/**
 * Checks if the form fields for adding a new doctor are set.
 * 
 * If the fields 'newDoctorLastName', 'newDoctorFirstName', and 'field' are set in the POST request,
 * it attempts to create a new doctor using the provided information. Depending on the result,
 * it sets the appropriate configuration for success or error.
 */
if (isset($_POST['newDoctorLastName'], $_POST['newDoctorFirstName'], $_POST['field'])) {
    AdminModel::createDoctor(); // Call the function to create a new doctor

    // Check if the doctor creation was successful
    if (Config::getDoctor() === null) {
        Config::setNewDoctorError(true); // Set an error flag in configuration if doctor creation failed
    } else {
        Config::setNewDoctorSuccess(true); // Set a success flag in configuration if doctor creation was successful
    }
}

/**
 * Checks if the form fields for changing planning are set.
 * 
 * If the fields 'planningId' and 'otherDoctorId' are set in the POST request,
 * it will execute the necessary logic to change the planning accordingly.
 */
if (isset($_POST['planningId'], $_POST['otherDoctorId'])) {
    // To be defined
}


/**
 * Sets the target path to the index page.
 *
 * @var string $targetPath The path to the index page.
 */
$targetPath = Config::getRootPath() . '/app/index.php';

// Check if the index page file exists. If not, display the maintenance view.
FileFunction::fileExists($targetPath, Config::getMaintenanceViewPath());
