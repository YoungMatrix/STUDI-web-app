<?php

// File verified

// Namespace declaration
namespace Classes;

// Use statements to include necessary classes.
use Classes\Person;

/**
 * Class Patient
 * 
 * Represents a patient entity with additional attributes.
 */
class Patient extends Person
{
    // Attributes
    private $address; // Address of the patient
    private $extendedInformation; // Extended information about the patient

    // Default role is 'patient' for Patient
    protected $role = 'patient';

    /**
     * Constructor for the Patient class.
     *
     * @param string $lastName Last name of the patient.
     * @param string $firstName First name of the patient.
     * @param string $email Email of the patient.
     * @param mixed $address Address of the patient.
     * @param mixed $extendedInformation Extended information about the patient (optional).
     */
    public function __construct($lastName, $firstName, $email, $address, $extendedInformation = null)
    {
        // Call the constructor of the parent class (Person)
        parent::__construct($lastName, $firstName, $email);
        $this->address = $address; // Set the address
        $this->extendedInformation = $extendedInformation; // Set the extended information
    }

    /**
     * Getter for the address.
     *
     * @return string Address of the patient.
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Getter for the extended information.
     *
     * @return mixed Extended information about the patient.
     */
    public function getExtendedInformation()
    {
        return $this->extendedInformation;
    }

    /**
     * Setter for the extended information.
     *
     * @param mixed $extendedInformation Extended information about the patient.
     */
    public function setExtendedInformation($extendedInformation)
    {
        $this->extendedInformation = $extendedInformation;
    }

    /**
     * Method to retrieve all attributes of the patient as an associative array.
     *
     * @return array Associative array containing all attributes.
     */
    public function getAllAttributes()
    {
        // Get all attributes from the parent class (Person)
        $parentAttributes = parent::getAllAttributes();

        // Add patient-specific attributes
        $patientAttributes = [
            'address' => $this->address,
            'extendedInformation' => $this->extendedInformation,
        ];

        // Merge both arrays
        $allAttributes = array_merge($parentAttributes, $patientAttributes);

        return $allAttributes;
    }
}
