-- Retrieve doctors ordered by id_doctor ascending
SELECT id_doctor, id_field, last_name_doctor, first_name_doctor, email_doctor
FROM doctor
ORDER BY id_doctor ASC;