<?php

// File verified

// Namespace declaration
namespace Classes;

// DateTime importation
use DateTime;

/**
 * Class History
 * 
 * Represents a history record especially for patient.
 */
class History
{
    // Attributes
    private $patientLastName; // Patient Last Name
    private $patternName; // Pattern Name
    private $fieldName; // Field Name
    private $fieldId; // Field ID
    private $doctorId; // Doctor ID
    private $doctorLastName; // Doctor Last Name 
    private $entranceDate; // Entrance date
    private $releaseDate; // Release date
    private $status; // History status (Coming, Completed, Ongoing)

    // Status Constants
    const STATUS_COMING = "À venir";
    const STATUS_ONGOING = "En cours";
    const STATUS_COMPLETED = "Effectué";

    /**
     * Constructor for the History class.
     * 
     * @param string $patientLastName Patient Last Name.
     * @param string $patternName Pattern Name.
     * @param string $fieldName Field Name.
     * @param int $fieldId Field ID.
     * @param string $doctorLastName Doctor Last Name.
     * @param int $doctorId Doctor ID.
     * @param string $entranceDate Entrance date.
     * @param string $releaseDate Release date.
     */
    public function __construct($patientLastName, $patternName, $fieldName, $fieldId, $doctorLastName, $doctorId, $entranceDate, $releaseDate)
    {
        $this->patientLastName = $patientLastName;
        $this->patternName = $patternName;
        $this->fieldName = $fieldName;
        $this->fieldId = $fieldId;
        $this->doctorLastName = $doctorLastName;
        $this->doctorId = $doctorId;
        $this->entranceDate = $entranceDate;
        $this->releaseDate = $releaseDate;
        $this->setStatus(); // Call method to determine status
    }

    // Getters

    /**
     * Getter for the Patient Last Name.
     * 
     * @return string Patient Last Name.
     */
    public function getPatientLastName()
    {
        return $this->patientLastName;
    }

    /**
     * Getter for the Pattern.
     * 
     * @return string Pattern.
     */
    public function getPatternName()
    {
        return $this->patternName;
    }

    /**
     * Getter for the Field.
     * 
     * @return string Field.
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Getter for the Field ID.
     * 
     * @return int Field ID.
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Getter for the Doctor Last Name.
     * 
     * @return string Doctor Last Name.
     */
    public function getDoctorLastName()
    {
        return $this->doctorLastName;
    }

    /**
     * Getter for the Doctor ID.
     * 
     * @return int Doctor ID.
     */
    public function getDoctorId()
    {
        return $this->doctorId;
    }

    /**
     * Getter for the Entrance date.
     * 
     * @return string Entrance date formatted as 'd/m/Y'.
     */
    public function getEntranceDate()
    {
        // Convert to DateTime and format
        $entranceDate = new DateTime($this->entranceDate);
        return $entranceDate->format('d/m/Y');
    }

    /**
     * Getter for the Release date.
     * 
     * @return string Release date formatted as 'd/m/Y'.
     */
    public function getReleaseDate()
    {
        // Convert to DateTime and format
        $releaseDate = new DateTime($this->releaseDate);
        return $releaseDate->format('d/m/Y');
    }

    /**
     * Getter for the status.
     * 
     * @return string History status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    // Private method

    /**
     * Method to determine the status based on the entrance date.
     */
    private function setStatus()
    {
        $today = new DateTime(); // Current date
        $entranceDate = new DateTime($this->entranceDate); // Entrance date
        $releaseDate = new DateTime($this->releaseDate); // Release date

        if ($entranceDate > $today) {
            $this->status = self::STATUS_COMING;
        } elseif ($entranceDate <= $today && $releaseDate > $today) {
            $this->status = self::STATUS_ONGOING;
        } elseif ($releaseDate <= $today) {
            $this->status = self::STATUS_COMPLETED;
        }
    }
}
