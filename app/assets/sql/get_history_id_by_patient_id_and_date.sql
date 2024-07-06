-- Retrieve the history id from the history table by patient id and date
SELECT id_history FROM history 
WHERE id_patient = :patientId 
AND date_entrance = :entranceDate
AND date_release = :releaseDate