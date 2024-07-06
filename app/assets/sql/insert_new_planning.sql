-- Insert a new planning record into the 'planning' table.
INSERT INTO planning (id_history, id_confirmed_doctor, date_planning) 
VALUES (:historyId, :confirmedDoctorId, :date);