-- Retrieve the number of entries with the new doctor for the same date
SELECT COUNT(*) FROM planning WHERE date_planning = :planningDate AND id_confirmed_doctor = :otherDoctorId;