-- Insert a new patient record into the 'patient' table.
INSERT INTO patient (id_role, last_name_patient, first_name_patient, address_patient, email_patient, password_patient, salt_patient) 
VALUES (:roleId, :name, :firstName, :address, :email, :password, :salt);
