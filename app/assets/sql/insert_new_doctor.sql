-- Insert a new doctor into the 'doctor' table.
INSERT INTO doctor (id_role, id_field, last_name_doctor, first_name_doctor, email_doctor, password_doctor, salt_doctor) 
VALUES (:roleId, :fieldId, :lastName, :firstName, :email, :password, :salt);