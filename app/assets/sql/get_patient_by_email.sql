-- Retrieve all columns from the 'patient' table for a specific email
SELECT *
FROM patient
WHERE email_patient = :email;
