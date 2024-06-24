<?php

// Namespace declaration
namespace Functions;

/**
 * Class FileHandler
 * Contains utility functions for file handling, including checking if a file exists and displaying a maintenance view.
 */
class FileHandler
{
    /**
     * Function to check if a file exists, otherwise display a maintenance view or a default message.
     *
     * This method checks if the target file exists. If it does, it includes it.
     * If it doesn't exist, it logs an error and, if a maintenance view path is provided and exists, includes it;
     * otherwise, it displays a default message.
     *
     * @param string $targetPath The path to the target file.
     * @param string $maintenanceViewPath The path to the maintenance view file.
     */
    public static function fileExists($targetPath, $maintenanceViewPath)
    {
        if (file_exists($targetPath)) {
            require_once $targetPath;
        } else {
            error_log('/!\ The file \'' . $targetPath . '\' does not exist /!\\');
            self::maintenanceFile($maintenanceViewPath);
        }
    }

    /**
     * Function to display a maintenance view or a default message if the maintenance view is missing.
     *
     * @param string $maintenanceViewPath The path to the maintenance view file.
     */
    public static function maintenanceFile($maintenanceViewPath)
    {
        if (file_exists($maintenanceViewPath)) {
            require_once $maintenanceViewPath;
        } else {
            error_log('/!\ The file \'' . $maintenanceViewPath . '\' does not exist /!\\');
            echo 'Page non trouvée : veuillez réessayer plus tard.';
        }
    }
}
