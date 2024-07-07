-- Retrieve the ID of a doctor based on provided details
SELECT id_doctor 
FROM doctor 
WHERE id_role = :roleId 
AND id_field = :fieldId 
AND last_name_doctor = :lastName 
AND first_name_doctor = :firstName 
AND email_doctor = :email 
AND password_doctor = :password 
AND salt_doctor = :salt;