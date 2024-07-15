# STUDI Web Application

## Requirements
- **Visual Studio Code** version >= 1.91.0
  - PHP Server Extension version >= 3.0.2
  - Composer Extension version >= 1.49.15728
  - Docker Extension version >= 1.29.1
  - GitHub Actions Extension version >= 0.26.3
  - vscode-pdf Extension version >= 1.2.2
- **XAMPP** version >= 8.2.12 with Control Panel version >= 3.3.0
- **Git** version >= 2.45.2
- **Docker Desktop** version >= 4.31.1
- **Flyctl commands** for windows, macOS or Linux.
- **Database connection parameters** (online or local database).
- **Public and Private keys** reCAPTCHA on Google in V2 invisible mode.

## Steps to Prepare the Application for Windows

### Clone Repository
1. Open Visual Studio Code.
2. In the terminal:
   ```bash
   git clone https://github.com/YoungMatrix/STUDI-web-app
3. Login to GIT if necessary.

### Setup Environment
4. In Visual Studio Code, open the directory directly in STUDI-web-app.
5. Create .env file in /app directory with the following content:
    # File verified

    # Maintenance mode enabled (true or false)
    MAINTENANCE_MODE=false

    # Database connection parameters (online or local database)
    DB_HOST=To Be Completed

    DB_ROOT=TBC

    DB_NAME=TBC

    DB_PASSWORD=TBC

    DB_PORT=TBC

    # Pepper used for hashing passwords
    PEPPER=Studi

    # Public reCAPTCHA key
    PUBLIC_RECAPTCHA_KEY=TBC

    # Secret reCAPTCHA key
    SECRET_RECAPTCHA_KEY=TBC

Note: Obtain reCAPTCHA keys by creating a reCAPTCHA on Google in V2 invisible mode.

6. Create /vendor directory in STUDI-web-app:
- Open composer.json.
- Run composer install in the terminal.

### Database Setup
7. Start Apache and MySQL from XAMPP Control Panel.
8. Open MySQL as admin.
9. In phpMyAdmin, create a new database named ecf_studi_verified.
10. Import the file ecf_studi_verified.sql from STUDI-web-app/db directory into the newly created database.

### Steps to Launch the Application for Windows
11. Go to index.php file in STUDI-web-app.
12. Right-click and select "PHP SERVER: reload server" to launch the application.
