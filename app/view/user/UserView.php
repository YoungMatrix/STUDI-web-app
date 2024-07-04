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
 * Holds the path to the user controller file.
 *
 * @var string $targetPath Path to the UserController.php file.
 */
$targetPath = '/app/controller/user/UserController.php';

/**
 * Start output buffering and include the public view file.
 * 
 * @require $_SERVER['DOCUMENT_ROOT'] . '/app/view/public/PublicView.php';
 */
ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/app/view/public/PublicView.php';
$userContent = ob_get_clean();

/**
 * Generate the user head section.
 *
 * This variable contains the HTML markup for the <head> section of the user interface.
 *
 * @var string $userHead The HTML markup for the <head> section with user styles.
 */
$userHead = '    <link rel="stylesheet" href="/app/view/user/user-style.css">
</head>';

/**
 * Generate the historical section based on extended information from a Person object.
 *
 * This code checks if extended information exists and generates HTML markup accordingly.
 *
 * @var string $historical The generated HTML markup for the historical section.
 * @var array $extendedInformation The extended information array from the Person object.
 * @var History $historyRecord The individual history record object.
 */
$extendedInformation = Config::getPerson()->getExtendedInformation();
if (Config::getPerson()->getExtendedInformation() !== null && is_array(Config::getPerson()->getExtendedInformation())) {
    $historical =   '<section class="historical">
                <a id=historicalJS>Historique</a>
                <section class="historicalList" id=historicalListJS>';
    $counter = 1;
    foreach ($extendedInformation as $historyRecord) {
        $historical .= '
                    <section class="history">
                        <div>Séjour ' . $counter . ':</div>
                        <div>Motif: ' . $historyRecord->getPatternName() . '</div>
                        <div>Spécialité: ' . $historyRecord->getFieldName() . '</div>
                        <div>Médecin: Dr. ' . $historyRecord->getDoctorLastName() . '</div>
                        <div>Date d\'entrée: ' . $historyRecord->getEntranceDate() . '</div>
                        <div>Date de sortie: ' . $historyRecord->getReleaseDate() . '</div>
                        <div>Statut: ' . $historyRecord->getStatus() . '</div>
                    </section>';
        $counter += 1;
    }
    $historical .= '
                </section>
            </section>';
} else {
    $historical =
        '<section class="historical">
                <a id=historicalJS>Historique</a>
                <section class="historicalList" id=historicalListJS>
                    <section class="history">
                        <p>Aucun historique</p>
                    </section>
                </section>
            </section>';
}

/**
 * Generate the logout button.
 *
 * @var string $logoutButton The HTML markup for the logout button.
 */
$logoutButton = '
            <section class="logout">
                <a id=logoutJS> Se déconnecter</a>
            </section>
';

/**
 * Generate the welcome message for the user.
 *
 * @var string $welcomeUser The HTML markup for the welcome message.
 */
$welcomeUser = '<h1>Bienvenue ' . Config::getPerson()->getLastName() . '</h1>
';

/**
 * Generate the appointment form content.
 *
 * @var string $appointmentContent The HTML markup for the appointment form.
 * @var string $targetPath The target path for form submission.
 * @var string $pattern The individual pattern name.
 * @var string $field The individual field name.
 */
$appointmentContent =
    '<a id=appointmentJS>Organiser mon séjour</a>
            <section class="appointmentForm" id=appointmentBoxJS>
                <h3>Veuillez remplir le formulaire suivant:</h3>
                <form id=appointmentFormJS action=' . $targetPath . ' method="post">
                    <div class="form-group">
                        <label for="entranceDate">Date d\'entrée:</label>
                        <input type="date" name="entranceDate" id=entranceDateJS min=' . date('Y-m-d', strtotime('+1 day')) . ' required>
                    </div>
                    <div class="form-group">
                        <label for="releaseDate">Date de sortie:</label>
                        <input type="date" name="releaseDate" id=releaseDateJS min=' . date('Y-m-d', strtotime('+2 day')) . ' required>
                    </div>   
                    <div class="form-group">
                        <label for="pattern">Motif:</label>
                        <select name="pattern" disabled id=patternJS required>
                            <option value="" disabled selected>Choisissez un motif</option>';
foreach (Config::getPatternList() as $pattern) {
    $appointmentContent .= '
                            <option value="' . $pattern . '">' . $pattern . '</option>';
}
$appointmentContent .= '
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="field">Spécialité:</label>
                        <select name="field" disabled id=fieldJS required>
                            <option value="" disabled selected>Choisissez une spécialité</option>';
foreach (Config::getFieldList() as $field) {
    $appointmentContent .= '
                            <option value="' . $field . '">' . $field . '</option>';
}
$appointmentContent .= '
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor">Médecin:</label>
                        <select name="doctor" disabled id=doctorJS required>
                            <option value="" disabled selected>Choisissez un médecin</option>
                        </select>
                    </div>
                    <button type="submit" id=submitAppointmentJS disabled>Confirmer</button>
                </form>
            </section>
';

// Generate the JavaScript content for user-related data.
$userContentJS = '<script>
        const doctorMap = ' . json_encode(Config::getDoctorMap()) . ';
        const appointmentSuccess = "' . Config::getAppointmentSuccess() . '";
        const appointmentError = "' . Config::getAppointmentError() . '";
    </script>
';

/**
 * Replace placeholders in the user content with generated content.
 *
 * @var string $userContent The HTML content template for the user section.
 * @var string $userHead The generated head section for the user.
 * @var string $welcome The placeholder for the welcome message.
 * @var string $welcomeUser The generated welcome message for the user.
 * @var string $signup The placeholder for the signup section.
 * @var string $historical The generated historical section.
 * @var string $login The placeholder for the login section.
 * @var string $logoutButton The generated logout button.
 * @var string $appointment The placeholder for the appointment message.
 * @var string $appointmentContent The generated appointment form content.
 * @var string $publicError The placeholder for the public error script.
 * @var string $userContentJS The generated JavaScript content for user-related data.
 */
$userContent = str_replace('</head>', $userHead, $userContent);
$userContent = str_replace($welcome, $welcomeUser, $userContent);
$userContent = str_replace($signup, $historical, $userContent);
$userContent = str_replace($login, $logoutButton, $userContent);
$userContent = str_replace($appointment, $appointmentContent, $userContent);
$userContent = str_replace($publicError, $userContentJS, $userContent);
$userContent = str_replace('/app/view/public/publicScript.js', '/app/view/user/userScript.js', $userContent);

/**
 * Output the dynamically generated user content to the browser.
 */
echo $userContent;
