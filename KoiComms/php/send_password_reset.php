<?php
include 'database.php';

// Use Composer's autoloader to handle all dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Define the classes we will be using
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load environment variables from the .env file in the project root
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Go up one directory to the project root
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die("Error: Could not find the .env file. Please ensure it exists in the project root directory and is readable.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $message = '';

    if (empty($email)) {
        $message = "Please enter your email address.";
        header("Location: ../components/forgot_password.php?message=" . urlencode($message));
        exit();
    }

    // Check if the email exists and belongs to an admin
    $sql = "SELECT id FROM users WHERE email = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = "If an admin account with that email exists, a password reset link has been sent.";
        header("Location: ../components/forgot_password.php?message=" . urlencode($message));
        exit();
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // Generate a unique token
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Store the token and expiration in the database
    $sql = "UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $token, $expires, $userId);

    if ($stmt->execute()) {
        // Dynamically generate the reset link to be portable
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $script_dir = dirname(dirname($_SERVER['PHP_SELF']));
        // Replace backslashes on Windows and ensure a single trailing slash
        $base_path = rtrim(str_replace('\\', '/', $script_dir), '/');
        
        $resetLink = $protocol . $host . $base_path . "/components/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $body = "Dear Admin,\n\n"
              . "You have requested a password reset for your account.\n"
              . "Please click on the following link to reset your password:\n"
              . $resetLink . "\n\n"
              . "This link will expire in 1 hour. If you did not request this, please ignore this email.\n\n"
              . "Regards,\n"
              . "Your Website Team";

        $mail = new PHPMailer(true);

        try {
            // Server settings from Environment Variables
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Uncomment for detailed debug output
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->Port       = (int)$_ENV['SMTP_PORT']; // Port should be an integer

            // Recipients
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            $message = "If an admin account with that email exists, a password reset link has been sent.";
            header("Location: ../components/forgot_password.php?message=" . urlencode($message));
            exit();

        } catch (Exception $e) {
            // For security, do not show detailed error in production
            // Log the error instead: error_log("Mailer Error: " . $mail->ErrorInfo);
            $message = "Message could not be sent. Please check your SMTP credentials in the .env file and contact the administrator."; 
            header("Location: ../components/forgot_password.php?error=" . urlencode($message));
            exit();
        }
    } else {
        $message = "Error generating reset link. Please try again later.";
        header("Location: ../components/forgot_password.php?error=" . urlencode($message));
        exit();
    }
} else {
    header("Location: ../components/forgot_password.php");
    exit();
}