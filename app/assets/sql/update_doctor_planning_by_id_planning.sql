-- Update the confirmed doctor ID in the planning table by id planning
UPDATE planning SET id_confirmed_doctor = :newConfirmedDoctorId WHERE id_planning = :planningId;