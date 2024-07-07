-- Retrieve the date, confirmed doctor and field ID by planning ID from the planning table
SELECT p.id_confirmed_doctor, p.date_planning, h.id_field
FROM planning AS p
INNER JOIN history AS h ON p.id_history = h.id_history
WHERE p.id_planning = :planningId;