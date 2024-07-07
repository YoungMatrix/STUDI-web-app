<?php

// File verified

/**
 * Define the path to the autoload file.
 * 
 * @var string $autoload Path to the autoload file.
 */
$autoload = $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $autoload;

// Use statements to include necessary classes.
use Configuration\Config;

/**
 * Define the welcome message.
 *
 * This message is displayed to welcome users when they access the application.
 *
 * @var string $welcome The HTML welcome message.
 */
$welcome = '<h1>Bienvenue</h1>';

/**
 * Define the signup section HTML.
 *
 * This variable contains the HTML markup for the signup form, including form fields and error messages.
 *
 * @var string $signup The HTML markup for the signup section.
 */
$signup = '<section class="signup">
                <a id=signupJS>S\'inscrire</a>
                <form class="signupForm" id=signupBoxJS action=/app/controller/user/UserController.php method="post">
                    <div class="form-group">
                        <label for="visitorLastName">Nom:</label>
                        <input type="text" name="visitorLastName" id=visitorLastNameJS required>
                    </div>
                    <div class="form-group">
                        <label for="visitorFirstName">Prénom:</label>
                        <input type="text" name="visitorFirstName" id=visitorFirstNameJS required>
                    </div>
                    <div class="form-group">
                        <label for="visitorAddress">Adresse:</label>
                        <input type="text" name="visitorAddress" id=visitorAddressJS required>
                    </div>
                    <div class="form-group">
                        <label for="visitorEmail">E-mail:</label>
                        <input type="email" name="visitorEmail" id=visitorEmailJS required>
                    </div>
                    <div class="form-group">
                        <label for="visitorPassword">Mot de passe:</label>
                        <input type="password" name="visitorPassword" id=visitorPasswordJS required>
                    </div>
                    <div class="g-recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="' . Config::getPublicReCaptchaKey() . '" data-callback="onSubmitReCaptchaSignup"></div>
                        <input type="hidden" name="g-recaptcha-response-signup" id=recaptchaSignupJS required>
                    </div>
                    <section class="signupHelp" id=signupHelpJS>
                        <p>Exemple d\'adresse:</p>
                        <p>25bis rue de l\'Église, 75017, Paris</p>
                    </section>
                    <section class="signupError" id=signupErrorJS>
                        <p>E-mail déjà utilisé ou saisie incorrecte</p>
                    </section>
                </form>
            </section>
';

/**
 * Define the login section HTML.
 *
 * This variable contains the HTML markup for the login form, including form fields and error messages.
 *
 * @var string $login The HTML markup for the login section.
 */
$login = '<section class="login">
                <a id=loginJS>Se connecter</a>
                <form class="loginForm" id=loginBoxJS action=/app/controller/user/UserController.php method="post">
                    <div class="form-group">
                        <label for="userEmail">E-mail:</label>
                        <input type="email" name="userEmail" id=userEmailJS required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Mot de passe:</label>
                        <input type="password" name="userPassword" id=userPasswordJS required>
                    </div>
                    <div class="g-recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="' . Config::getPublicReCaptchaKey() . '" data-callback="onSubmitReCaptchaLogin"></div>
                        <input type="hidden" name="g-recaptcha-response-login" id=recaptchaLoginJS required>
                    </div>
                    <section class="loginError" id=loginErrorJS>
                        <p>Saisie incorrecte</p>
                    </section>
                </form>
            </section>
';

/**
 * Define the appointment message.
 *
 * This variable contains HTML markup for displaying a message prompting users to log in
 * for appointment booking.
 *
 * @var string $appointment The HTML markup for the appointment message.
 */
$appointment = '<h3 id=appointmentJS>Pour obtenir un séjour, veuillez-vous connecter.</h3>
';

/**
 * Define JavaScript variables for error messages.
 *
 * These JavaScript variables store error messages related to signup and login actions.
 *
 * @var string $publicError The JavaScript code snippet containing error messages for signup and login.
 */
$publicError = '<script>
        const signupError = "' . Config::getSignupError() . '";
        const loginError = "' . Config::getLoginError() . '";
    </script>
';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/app/view/public/public-style.css">
</head>

<body>
    <!-- Begin header -->
    <header>
        <a class="logo"> Hôpital <span>SoigneMoi</span></a>
        <section class="utility">
            <?php echo $signup; ?>
            <?php echo $login; ?>
        </section>
    </header>
    <!-- End header -->

    <!-- Begin home -->
    <section class="home">
        <section class="sub-home">
            <?php echo $welcome; ?>
            <p>
                <span>SoigneMoi</span> est un hôpital de la région lilloise (dans le nord de la France).
                <br> Disponible 24h sur 7 jours, nous accueillons du personnel de qualité
                mais aussi des patients ayant besoin de soin.
            </p>
            <h2 class="title">Prestations</h2>
            <p class="sub-title">Notre centre de santé propose les prestations suivantes:</p>
            <section class="serviceList">
                <div>Médecine Générale</div>
                <div>Chirurgie</div>
                <div>Médecine Dentaire</div>
                <div>Dermatologie</div>
                <div>Gynécologie</div>
                <div>Ophtalmologie</div>
            </section>
            <?php echo $appointment; ?>
        </section>
    </section>
    <!-- End home -->

    <!-- Begin footer -->
    <section class="footer">
        <h1>Contactez-nous</h1>
        <section class="contactdetails">
            <ul class="information">
                <a><span>Horaire:</span> 24/24h 7/7j</a>
                <a><span>Numéro:</span> +33 1 42 45 25 3X</a>
            </ul>
            <ul class="localisation">
                <p>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2623.9655450227046!2d2.3243784771617406!3d48.877933371334834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e4a5ddc12cd%3A0xd315f7873ace14ea!2s32%20Rue%20d&#39;Amsterdam%2C%2075009%20Paris!5e0!3m2!1sfr!2sfr!4v1714206805225!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </p>
            </ul>
            <ul>
                <p>SoigneMoi - 2024 ©</p>
            </ul>
        </section>
    </section>
    <!-- End footer -->

    <?php echo $publicError; ?>
    <script>
        function onSubmitReCaptchaSignup(token) {
            var recaptchaSignupJS = 'recaptchaSignupJS';
            document.getElementById(recaptchaSignupJS).value = token;
            var signupForm = document.getElementById('signupBoxJS');
            signupForm.submit();
        }

        function onSubmitReCaptchaLogin(token) {
            var recaptchaLoginJS = 'recaptchaLoginJS';
            document.getElementById(recaptchaLoginJS).value = token;
            var loginForm = document.getElementById('loginBoxJS');
            loginForm.submit();
        }
    </script>
    <script type="module" src="/app/view/public/publicScript.js"></script>
</body>

</html>