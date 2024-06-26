<?php

// File verified

/**
 * Define the root path of the web server and the path to the autoload file.
 * 
 * @var string $rootPath Root path of the web server.
 * @var string $autoload Path to the autoload file.
 */
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$autoload = $rootPath . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;
use PHPFunctions\FileHandler;

// Initialize the configuration.
Config::init();

/**
 * Define the paths for the public and maintenance view files.
 *
 * @var string $publicViewPath Path to the public view file.
 * @var string $maintenanceViewPath Path to the maintenance view file.
 */
$publicViewPath = $rootPath . '/app/view/public/PublicView.php';
$maintenanceViewPath = $rootPath . '/app/view/maintenance/MaintenanceView.php';

// Check if the public view file exists. If not, display the maintenance view.
FileHandler::fileExists($publicViewPath, $maintenanceViewPath);
