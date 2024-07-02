-- Retrieves the history records for a specific patient from the 'history' table,
-- filtering by the patient's ID and ordering the results by the entrance date in ascending order.
SELECT id_patient, id_pattern, id_field, id_doctor, date_entrance, date_release 
FROM history 
WHERE id_patient = :patientId 
ORDER BY date_entrance ASC;
