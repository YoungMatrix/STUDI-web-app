-- Retrieve planning (with history) records based on date and ordered by ASC date
SELECT p.*, h.*
FROM planning p
INNER JOIN history h ON p.id_history = h.id_history
WHERE p.date_planning >= :today
ORDER BY p.date_planning ASC;
