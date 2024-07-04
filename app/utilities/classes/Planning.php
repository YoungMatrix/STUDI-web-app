<?php

// File verified

// Namespace declaration
namespace Classes;

// DateTime importation
use DateTime;

// Use statements to include necessary classes.
use Classes\History;

/**
 * Class Planning
 * 
 * Represents a planning record extending from History.
 */
class Planning extends History
{
    // Additional attributes
    private $id;
    private $planningDate; // Planning Date

    /**
     * Constructor for the Planning class.
     *
     * @param int $id Identifier.
     * @param string $patientLastName Patient Last Name.
     * @param string $patternName Pattern Name.
     * @param string $fieldName Field Name.
     * @param int $fieldId Field ID.
     * @param string $doctorLastName Doctor Last Name.
     * @param int $doctorId Doctor ID.
     * @param string $entranceDate Entrance date.
     * @param string $releaseDate Release date.
     * @param string $planningDate Planning date.
     */
    public function __construct($id, $patientLastName, $patternName, $fieldName, $fieldId, $doctorLastName, $doctorId, $entranceDate, $releaseDate, $planningDate)
    {
        parent::__construct($patientLastName, $patternName, $fieldName, $fieldId, $doctorLastName, $doctorId, $entranceDate, $releaseDate);
        $this->id = $id;
        $this->planningDate = $planningDate;
    }

    /**
     * Getter for the Identifier.
     *
     * @return int Identifier.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for the Planning Date.
     *
     * @return string Planning date formatted as 'd/m/Y'.
     */
    public function getPlanningDate()
    {
        // Convert to DateTime and format
        $planningDate = new DateTime($this->planningDate);
        return $planningDate->format('d/m/Y');
    }
}
