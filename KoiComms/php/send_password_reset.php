<?php
include 'database.php';
require 'smtp_config.php'; // Include the new SMTP config file

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../php/PHPMailer/src/Exception.php';
require '../php/PHPMailer/src/PHPMailer.php';
require '../php/PHPMailer/src/SMTP.php';

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
        // Send email using PHPMailer
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/koicomms/KoiComms/components/reset_password.php?token=" . $token;
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
            // Server settings from smtp_config.php
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;

            // Recipients
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
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
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            exit();
        }
    } else {
        $message = "Error generating reset link. Please try again later.";
        header("Location: ../components/forgot_password.php?message=" . urlencode($message));
        exit();
    }

    $stmt->close();
    $conn->close();
    header("Location: ../components/forgot_password.php?message=" . urlencode($message));
    exit();
} else {
    header("Location: ../components/forgot_password.php");
    exit();
}
