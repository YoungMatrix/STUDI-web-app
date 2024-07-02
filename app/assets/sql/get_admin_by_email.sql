-- Retrieve admin data by email
SELECT *
FROM admin
WHERE email_admin = :email;
