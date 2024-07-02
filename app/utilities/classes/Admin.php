<?php

// File verified

// Namespace declaration
namespace Classes;

// Use statements to include necessary classes.
use Classes\Person;

/**
 * Class Admin
 * 
 * Represents an admin entity with extended information.
 */
class Admin extends Person
{
    // Default role is 'admin' for Admin
    protected $role = 'admin';
}
