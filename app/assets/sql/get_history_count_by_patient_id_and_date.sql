-- Retrieve the count of records from the history table
SELECT COUNT(*) FROM history 
WHERE id_patient = :patientId 
AND (:entranceDate BETWEEN date_entrance AND date_release
OR :releaseDate BETWEEN date_entrance AND date_release);