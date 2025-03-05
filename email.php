<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function loadEnv($path = '.env') {
    if (!file_exists($path)) {
        throw new Exception('The .env file does not exist.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignore comments (lines starting with # or ;), and handle empty lines
        if (empty($line) || $line[0] == '#' || $line[0] == ';') {
            continue;
        }

        // Split by the first equal sign to extract key and value
        list($key, $value) = explode('=', $line, 2);

        // Remove surrounding whitespaces
        $key = trim($key);
        $value = trim($value);

        // Store in $_ENV
        $_ENV[$key] = $value;

        // Optionally, you can use putenv to set them as environment variables too
        putenv("$key=$value");
    }
}

// Load the .env file
loadEnv();

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'assimnaim04@gmail.com'; // Your email
        $mail->Password   = $_ENV['PASSWORD']; // Use an App Password, NOT your real password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
        $mail->Port       = 587;

        // Email Settings
        $mail->setFrom('assimnaim04@gmail.com', 'AgendaESTSB');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body    = $message;

        // Send Email
        $mail->send();
        echo "Email sent successfully!";
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


// sendEmail("ackrmanlevi62@gmail.com", "Test Email", "<h1>Hello, this is a test email</h1>");
?>
