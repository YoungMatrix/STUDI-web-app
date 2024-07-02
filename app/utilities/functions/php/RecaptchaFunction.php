<?php

// Namespace declaration
namespace PHPFunctions;

/**
 * Class RecaptchaFunction
 * 
 * A utility class for verifying reCAPTCHA tokens using Google's API.
 */
class RecaptchaFunction
{
    /**
     * Function to verify reCAPTCHA token with Google API.
     *
     * @param string $token The reCAPTCHA token to verify.
     * @param string $secretReCaptchaKey The secret key provided by Google reCAPTCHA.
     * @return bool Returns true if the token is valid, otherwise false.
     */
    public static function verifyRecaptchaToken($token, $secretReCaptchaKey)
    {
        // URL to Google's reCAPTCHA verification endpoint.
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        // Data to be sent in POST request to Google.
        $data = array(
            'secret' => $secretReCaptchaKey,
            'response' => $token
        );

        // Initialize cURL session.
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // Execute cURL session and get response.
        $response = curl_exec($ch);

        // Close cURL session.
        curl_close($ch);

        // Decode JSON response from Google.
        $responseData = json_decode($response);

        // Check if the reCAPTCHA response is valid.
        return isset($responseData->success) && $responseData->success === true;
    }
}
