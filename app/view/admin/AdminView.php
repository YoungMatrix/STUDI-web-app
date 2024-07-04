<?php

// File verified

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes
use Configuration\Config;

/**
 * Holds the path to the admin controller file.
 *
 * @var string $targetPath Path to the AdminController.php file.
 */
$targetPath = '/app/controller/admin/AdminController.php';

/**
 * Start output buffering and include the public view file.
 * 
 * @require $_SERVER['DOCUMENT_ROOT'] . '/app/view/public/PublicView.php';
 */
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/app/view/public/PublicView.php';
$adminContent = ob_get_clean();

/**
 * Generate the admin head section.
 *
 * @var string $adminHead The generated HTML for the admin head section.
 */
$adminHead = '    <link rel="stylesheet" href="/app/view/admin/admin-style.css">
</head>';

/**
 * Generate the historical section based on planning records.
 *
 * This section displays a list of planning records with details such as ID, date, patient, specialty, doctor ID, and doctor name.
 * If there are no planning records, it displays a message indicating no records.
 *
 * @var array $planningRecords The array containing planning records.
 * @var string $historical The HTML markup for the historical section.
 * @var Planning $planning A Planning object representing a single planning record.
 */
$planningRecords = Config::getPlanningRecords();
if (!empty($planningRecords)) {
    $historical =   '<section class="historical">';
    if (count($planningRecords) === 1) {
        $historical .= '
                <a id=planningJS>Planning</a>
                ';
    } else {
        $historical .= '
                <a id=planningJS>Plannings</a>
                ';
    }
    $historical .= '<section class="planningList" id=planningListJS>';
    foreach ($planningRecords as $planning) {
        $historical .= '
                    <section class="history">
                        <div>Planning: ' . $planning->getId() . '</div>
                        <div>Date: ' . $planning->getPlanningDate() . '</div>
                        <div>Patient: ' . $planning->getPatientLastName() . '</div>
                        <div>Spécialité: ' . $planning->getFieldName() . '</div>
                        <div>Matricule: ' . $planning->getDoctorId() . '</div>
                        <div>Docteur: ' . $planning->getDoctorLastName() . '</div>
                    </section>';
    }
    $historical .= '
                </section>
            </section>
            ';
} else {
    $historical =
        '<section class="historical">
                <a id=planningJS>Planning</a>
                <section class="planningList" id=planningListJS>
                    <section class="history">
                        <p>Aucun planning</p>
                    </section>
                </section>
            </section>
            ';
}

/**
 * Generate the doctor list section based on provided doctor records.
 *
 * This function generates a section displaying a list of doctors with details such as ID, specialty, last name,
 * first name, and email. If no doctor records are provided, it displays a message indicating no doctors.
 *
 * @param array $doctorRecords The array containing doctor records.
 * @var string $historical The HTML markup for the historical section.
 * @var Doctor $doctor A Doctor object representing a single doctor record.
 */
$doctorRecords = Config::getDoctorRecords();
if (!empty($doctorRecords)) {
    $historical .= '<section class="historical">';
    if (count($doctorRecords) === 1) {
        $historical .= '
                <a id=doctorJS>Docteur</a>
                ';
    } else {
        $historical .= '
                <a id=doctorJS>Docteurs</a>
                ';
    }
    $historical .= '<section class="doctorList" id=doctorListJS>';
    foreach ($doctorRecords as $doctor) {
        $historical .= '
                    <section class="history">
                        <div>Matricule: ' . $doctor->getMatricule() . '</div>
                        <div>Spécialité: ' . $doctor->getField() . '</div>
                        <div>Nom: ' . $doctor->getLastName() . '</div>
                        <div>Prénom: ' . $doctor->getFirstName() . '</div>
                        <div>E-mail: ' . $doctor->getEmail() . '</div>
                    </section>';
    }
    $historical .= '
                </section>
            </section>';
} else {
    $historical .=
        '<section class="historical">
            <a id=doctorJS>Docteur</a>
            <section class="doctorList" id=doctorListJS>
                <section class="history">
                    <p>Aucun docteur</p>
                </section>
            </section>
        </section>
        ';
}

/**
 * Generate the logout button HTML.
 *
 * This function creates HTML markup for a logout button within a section element.
 *
 * @param string $logoutButton The HTML markup for the logout button.
 */
$logoutButton = '
            <section class="logout">
                <a id=logoutJS> Se déconnecter</a>
            </section>
';

/**
 * Generate the welcome message for the admin.
 *
 * This function creates an HTML heading to welcome the admin.
 *
 * @param string $welcomeAdmin Static content for the admin welcome message.
 */
$welcomeAdmin = '<h1>Bienvenue ADMIN</h1>
';

/**
 * Generate the form content for adding a new doctor and modifying doctor planning.
 * 
 * This function dynamically generates HTML markup for two forms:
 * 1. Form for adding a new doctor.
 * 2. Form for modifying doctor planning if both doctor records and planning records are available.
 *
 * @var string $formContent The generated HTML markup for the forms.
 * @var string $targetPath The target path for form submission.
 * @var string $field The field variable description.
 * @var Planning $planning The Planning object for doctor planning details.
 * @var Doctor $doctor The Doctor object for doctor details.
 */
$formContent =
    '<a id=newDoctorJS>Ajouter un nouveau docteur</a>
            <section class="newDoctorForm" id=newDoctorBoxJS>
                <h3>Veuillez remplir le formulaire suivant:</h3>
                <form id=newDoctorFormJS action=' . $targetPath . ' method="post">
                    <div class="form-group">
                        <label for="newDoctorLastName">Nom du docteur:</label>
                        <input type="text" name="newDoctorLastName" id=newDoctorLastNameJS required>
                    </div>
                    <div class="form-group">
                        <label for="newDoctorFirstName">Prénom du docteur:</label>
                        <input type="text" name="newDoctorFirstName" id=newDoctorFirstNameJS required>
                    </div>
                    <div class="form-group">
                        <label for="field">Spécialité:</label>
                        <select name="field" id=fieldJS required>
                            <option value="" disabled selected>Choisissez une spécialité</option>';
foreach (Config::getFieldList() as $field) {
    $formContent .= '
                            <option value="' . $field . '">' . $field . '</option>';
}
$formContent .= '
                        </select>
                    </div>
                    <button type="submit" id=submitNewDoctorJS disabled>Confirmer</button>  
                </form>
            </section>
            ';

if (!empty($doctorRecords) && !empty($planningRecords)) {
    $formContent .= '<a id=doctorPlanningJS>Modifier le planning des docteurs</a>
            <section class="doctorPlanningForm" id=doctorPlanningBoxJS>
                <h3>Veuillez remplir le formulaire suivant:</h3>
                <form id=doctorPlanningFormJS action=' . $targetPath . ' method="post">
                    <div class="form-group">
                        <label for="planningId">Planning:</label>
                        <select name="planningId" id=planningIdJS required>
                            <option value="" disabled selected>Choisissez un identifiant</option>';
    foreach ($planningRecords as $planning) {
        $formContent .= '
                            <option value="' . $planning->getId() . '">' . $planning->getId() . ' - ' . $planning->getPlanningDate() . ' par ' . $planning->getDoctorId() . ' - Dr. ' . $planning->getDoctorLastName() . '</option>';
    }
    $formContent .= '
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="otherDoctorId">Remplacer par:</label>
                        <select name="otherDoctorId" id=otherDoctorIdJS disabled required>
                            <option value="" disabled selected>Choisissez un docteur</option>';
    foreach ($doctorRecords as $doctor) {
        $formContent .= '
                            <option value="' . $doctor->getMatricule() . '">' . $doctor->getMatricule() . ' - Dr. ' . $doctor->getLastName() . '</option>';
    }
    $formContent .= '
                        </select>
                    </div>
                    <button type="submit" id=submitDoctorPlanningJS disabled>Confirmer</button>  
                </form>
            </section>
';
} else {
    $formContent .= '<h3>Aucun planning en cours.</h3>
';
}

/**
 * Generate the admin error script.
 * 
 * This script generates JavaScript variables containing error and success flags related to admin operations.
 * These variables are used to handle and display success and error messages dynamically on the client side.
 * 
 * @var string $adminError The generated JavaScript script containing error and success flags.
 */
$adminError = '<script>
        const newDoctorSuccess = "' . Config::getNewDoctorSuccess() . '";
        const newDoctorError = "' . Config::getNewDoctorError() . '";
        const changePlanningSuccess = "' . Config::getChangePlanningSuccess() . '";
        const changePlanningError = "' . Config::getChangePlanningError() . '";
    </script>
';

/**
 * Replace placeholders in the admin content with generated content.
 *
 * @var string $adminContent The HTML content template for the admin section.
 * @var string $adminHead The generated head section for the admin.
 * @var string $welcome The placeholder for the welcome message.
 * @var string $welcomeAdmin The generated welcome message for the admin.
 * @var string $signup The placeholder for the signup section.
 * @var string $historical The generated historical section.
 * @var string $login The placeholder for the login section.
 * @var string $logoutButton The generated logout button.
 * @var string $formContent The generated form content for appointments or other forms.
 * @var string $publicError The placeholder for the public error script.
 * @var string $adminError The generated JavaScript content for admin-related errors.
 */
$adminContent = str_replace('</head>', $adminHead, $adminContent);
$adminContent = str_replace($welcome, $welcomeAdmin, $adminContent);
$adminContent = str_replace($signup, $historical, $adminContent);
$adminContent = str_replace($login, $logoutButton, $adminContent);
$adminContent = str_replace($appointment, $formContent, $adminContent);
$adminContent = str_replace($publicError, $adminError, $adminContent);
$adminContent = str_replace('/app/view/public/publicScript.js', '/app/view/admin/adminScript.js', $adminContent);

/**
 * Output the modified admin content.
 */
echo $adminContent;
