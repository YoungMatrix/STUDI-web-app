<?php

// Namespace declaration
namespace PHPFunctions;

/**
 * Class VerificationFunction
 * 
 * Contains functions for verifying data formats like alphabetical characters, email addresses, and complete addresses.
 */
class VerificationFunction
{
    /**
     * Function to verify if all characters in a word are alphabetical.
     *
     * This function removes whitespace from the word and uses a regex pattern to check if all characters are letters.
     *
     * @param string $word The word to check.
     * @return bool Returns true if all characters are alphabetical, otherwise false.
     */
    public static function areAllCharactersAlpha($word)
    {
        // Remove whitespace from the word
        $word = str_replace(' ', '', $word);

        // Check if all characters are alphabetical using regex
        return preg_match('/^\p{L}+$/u', $word);
    }

    /**
     * Function to verify the format of an email address.
     *
     * @param string $email The email address to validate.
     * @return bool Returns true if the email address format is valid, otherwise false.
     */
    public static function verifyEmail($email)
    {
        // Remove leading and trailing whitespace
        $email = trim($email);

        // Validate email format using PHP filter_var function
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to verify the complete address of a new visitor.
     *
     * This function uses regex patterns to verify the format of the address, postal code, and city.
     *
     * @param string $completeAddress The complete address of the visitor.
     * @return bool Returns true if the address format is valid, otherwise false.
     */
    public static function verifyAddress($completeAddress)
    {
        try {
            // Regex patterns for address, postal code, and city
            $regexAddress = '/^\d{1,5}(?:ter|bis|quater|quinquies|sexies|septies|octies|novies|décies)?\s(?:rue|avenue|boulevard|impasse|place|allée|route)\s(?:d\’?|de\s)?[\p{L} \-]?(?:[\'][\p{L} -]+)?+/u';
            $regexCode = '/^\d{5}$/';
            $regexCity = '/^[\p{L}\'\- ]+$/u';

            // Split the complete address into components
            $components = explode(',', $completeAddress);

            // Ensure there are at least 3 components (address, code, city)
            if (count($components) >= 3) {
                $address = trim($components[0]);
                $code = trim($components[1]);
                $city = trim($components[2]);

                // Validate each component against its respective regex pattern
                return preg_match($regexAddress, $address) && preg_match($regexCode, $code) && preg_match($regexCity, $city);
            } else {
                return false; // Return false if there are not enough components
            }
        } catch (\Exception $e) {
            // Log exceptions related to address validation
            error_log('Address not valid. ' . $e->getMessage());

            // Return false in case of error
            return false;
        }
    }
}
