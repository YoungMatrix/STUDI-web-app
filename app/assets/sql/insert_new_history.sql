-- Insert a new history record into the 'history' table.
INSERT INTO history (id_patient, id_pattern, id_field, id_doctor, date_entrance, date_release) 
VALUES (:patient, :pattern, :field, :doctor, :entranceDate, :releaseDate);