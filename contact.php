
<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación reCAPTCHA con cURL
    $recaptchaSecret = '6LfJGWMrAAAAAF2Wz68UIcy4pu4gTWKb3qVzV-1j';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    $recaptchaValid = false;

    if (!empty($recaptchaResponse)) {
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        $captchaSuccess = json_decode($response);
        $recaptchaValid = $captchaSuccess->success ?? false;
    }

    if (!$recaptchaValid) {
        $error = "Error de verificación reCAPTCHA. Por favor, inténtalo nuevamente.";
    } else {
        // Entrada
