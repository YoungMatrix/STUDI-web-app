<?php

// File verified

// Namespace declaration
namespace UserController;

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

/**
 * Handles user signup.
 *
 * Validates and processes user signup based on POST data. Sets signup error if user creation fails.
 */
if (isset($_POST['visitorLastName'], $_POST['visitorFirstName'], $_POST['visitorAddress'], $_POST['visitorEmail'], $_POST['visitorPassword'], $_POST['g-recaptcha-response-signup'])) {
    UserModel::processSignup(); // Process the signup attempt

    Config::setLoginError(false); // Clear login error flag

    // Check if user creation was successful
    if (Config::getPerson() === null) {
        Config::setSignupError(true); // Set signup error flag
    } else {
        Config::setSignupError(false); // Clear signup error flag
    }
}

/**
 * Handles user login.
 *
 * Validates and processes user login based on POST data. Sets login error if login fails.
 */
if (isset($_POST['userEmail'], $_POST['userPassword'], $_POST['g-recaptcha-response-login'])) {
    UserModel::processLogin(); // Process the login attempt

    Config::setSignupError(false); // Clear signup error flag

    // Check if login was successful
    if (Config::getPerson() === null) {
        Config::setLoginError(true); // Set login error flag
    } else {
        Config::setLoginError(false); // Clear login error flag
    }
}

/**
 * Sets the target path to the index page.
 *
 * @var string $targetPath The path to the index page.
 */
$targetPath = Config::getRootPath() . '/app/index.php';

// Check if the index page file exists. If not, display the maintenance view.
FileFunction::fileExists($targetPath, Config::getMaintenanceViewPath());
