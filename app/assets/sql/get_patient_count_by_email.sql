-- Retrieve the number of patients with a specific email
SELECT COUNT(*)
FROM patient
WHERE email_patient = :email;
