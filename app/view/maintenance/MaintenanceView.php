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

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de maintenance</title>
    <link rel="stylesheet" href="<?php echo Config::getMaintenanceStylePath(); ?>">
</head>

<body>
    <h1>Désolé, nous sommes actuellement en maintenance.</h1>
    <p>Nous serons bientôt de retour. Merci pour votre compréhension.</p>
</body>

</html>