<?php

// File verified

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes
use Configuration\Config;
use PHPFunctions\FileFunction;

// Initialize the configuration
Config::init();

/**
 * Determine the target path based on the user role.
 *
 * If a user is logged in, determine the appropriate target path based on their role.
 * For patients, the user view path is used. For admins, the admin view path is used.
 * If no user is logged in, the public view path is used.
 *
 * @param object|null $person The user object or null if no user is logged in.
 * @return string $targetPath The determined target path.
 */
if (Config::getPerson() !== null) {
    // Check the role of the person object
    if (Config::getPerson()->getRole() === 'patient') {
        // If the role is 'patient', set the target path to the user view
        $targetPath = Config::getRootPath() . '/app/view/user/UserView.php';
    } elseif (Config::getPerson()->getRole() === 'admin') {
        // If the role is 'admin', set the target path to the admin view
        $targetPath = Config::getRootPath() . '/app/view/admin/AdminView.php';
    }
} else {
    // If no user is logged in or 'person' object is not set in session, set the target path to the public view
    $targetPath = Config::getRootPath() . '/app/view/public/PublicView.php';
}

// Check if the target path file exists. If not, display the maintenance view.
FileFunction::fileExists($targetPath, Config::getMaintenanceViewPath());
