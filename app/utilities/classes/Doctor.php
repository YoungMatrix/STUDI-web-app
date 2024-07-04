<?php

// File verified

// Namespace declaration
namespace Classes;

// Use statements to include necessary classes.
use Classes\Person;

/**
 * Class Doctor
 * 
 * Represents a doctor entity with extended information.
 */
class Doctor extends Person
{
    // Attributes
    private $matricule; // Matricule of the doctor
    private $field; // Field of specialization of the doctor

    // Role of the doctor
    protected $role = 'docteur';

    /**
     * Constructor for the Doctor class.
     * 
     * @param string $lastName Last name of the doctor.
     * @param string $firstName First name of the doctor.
     * @param string $email Email of the doctor.
     * @param string $matricule Matricule of the doctor.
     * @param string $field Field of specialization of the doctor.
     */
    public function __construct($lastName, $firstName, $email, $matricule, $field)
    {
        // Call the constructor of the parent class (Person)
        parent::__construct($lastName, $firstName, $email);
        $this->matricule = $matricule; // Set the matricule
        $this->field = $field; // Set the field of specialization
    }

    /**
     * Getter for the matricule.
     * 
     * @return string Matricule of the doctor.
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Getter for the field of specialization.
     * 
     * @return string Field of specialization of the doctor.
     */
    public function getField()
    {
        return $this->field;
    }
}
