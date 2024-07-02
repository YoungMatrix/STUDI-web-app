<?php

// File verified

// Namespace declaration
namespace Classes;

/**
 * Class Person
 * 
 * Represents a person entity with basic information.
 */
class Person
{
    // Attributes
    private $lastName; // Last name of the person
    private $firstName; // First name of the person
    private $email; // Email of the person

    // Default role is empty for generic Person
    protected $role = '';

    /**
     * Constructor for the Person class.
     *
     * @param string $lastName Last name of the person.
     * @param string $firstName First name of the person.
     * @param string $email Email of the person.
     */
    public function __construct($lastName, $firstName, $email)
    {
        // Initialize the attributes
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
    }

    // Getters

    /**
     * Getter for the last name.
     *
     * @return string Last name of the person.
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Getter for the first name.
     *
     * @return string First name of the person.
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Getter for the email.
     *
     * @return string Email of the person.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Getter for the role.
     *
     * @return string Role of the person.
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Method to retrieve all attributes of the person as an associative array.
     *
     * @return array Associative array containing all attributes.
     */
    public function getAllAttributes()
    {
        return [
            'lastName' => $this->lastName,
            'firstName' => $this->firstName,
            'email' => $this->email,
            'role' => $this->role
        ];
    }
}
