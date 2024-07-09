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

// Unset all session variables
session_unset();

/**
 * Sets the target path to the index page.
 *
 * @var string $targetPath The path to the index page.
 */
$targetPath = Config::getRootPath() . '/index.php';

// Check if the index page file exists. If not, display the maintenance view
FileFunction::fileExists($targetPath, Config::getMaintenanceViewPath());
