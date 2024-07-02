<?php

// File verified

echo "<h1>UserView.php:</h1>";

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;

// Initialize the configuration.
Config::init();

// Print all attributes
echo '<h2>Patient Attributes:</h2>';
echo '<pre>';
print_r(Config::getPerson()->getAllAttributes());
echo '</pre>';

// Display pattern list
echo '<h2>Pattern List:</h2>';
echo '<pre>' . print_r(Config::getPatternList(), true) . '</pre>';

// Display field list
echo '<h2>Field List:</h2>';
echo '<pre>' . print_r(Config::getFieldList(), true) . '</pre>';

// Display doctor map
echo '<h2>Doctor Map:</h2>';
echo '<pre>' . print_r(Config::getDoctorMap(), true) . '</pre>';

// Display signup error
echo '<h2>Signup Error:</h2>';
if (Config::getSignupError()) {
    echo "True";
} else {
    echo "False";
}

// Display login error
echo '<h2>Login Error:</h2>';
if (Config::getLoginError()) {
    echo "True";
} else {
    echo "False";
}
