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
use UserModel\UserModel;
use PHPFunctions\FileFunction;

// Initialize the configuration.
Config::init();

// Resetting the login error flag to false.
Config::setLoginError(false);

// Resetting the signup error flag to false.
Config::setSignupError(false);

// Resetting the appointment success flag to false.
Config::setAppointmentSuccess(false);

// Resetting the appointment error flag to false.
Config::setAppointmentError(false);

/**
 * Handles user signup.
 *
 * Validates and processes user signup based on POST data. Sets signup error if user creation fails.
 */
if (isset($_POST['visitorLastName'], $_POST['visitorFirstName'], $_POST['visitorAddress'], $_POST['visitorEmail'], $_POST['visitorPassword'], $_POST['g-recaptcha-response-signup'])) {
    UserModel::processSignup(); // Process the signup attempt

    // Check if user creation was successful
    if (Config::getPerson() === null) {
        Config::setSignupError(true); // Set signup error flag
    }
}

/**
 * Handles user login.
 *
 * Validates and processes user login based on POST data. Sets login error if login fails.
 */
if (isset($_POST['userEmail'], $_POST['userPassword'], $_POST['g-recaptcha-response-login'])) {
    UserModel::processLogin(); // Process the login attempt

    // Check if login was successful
    if (Config::getPerson() === null) {
        Config::setLoginError(true); // Set login error flag
    }
}

/**
 * Handles appointment creation.
 *
 * Checks if the required POST variables are set for appointment creation.
 * If all required POST variables are set, attempts to add the appointment using UserModel::addAppointment().
 * Clears login and signup error flags.
 * If saved history exists, sets appointment success; otherwise, sets appointment error.
 */
if (isset($_POST['pattern'], $_POST['field'], $_POST['doctor'], $_POST['entranceDate'], $_POST['releaseDate'])) {
    UserModel::addAppointment(); // Call UserModel method to add appointment

    // Check if saved history exists
    if (Config::getSavedHistory() !== null) {
        Config::setAppointmentSuccess(true); // Set appointment success flag
    } else {
        Config::setAppointmentError(true); // Set appointment error flag
    }
}

/**
 * Sets the target path to the index page.
 *
 * @var string $targetPath The path to the index page.
 */
$targetPath = Config::getRootPath() . '/index.php';

// Check if the index page file exists. If not, display the maintenance view.
FileFunction::fileExists($targetPath, Config::getMaintenanceViewPath());
