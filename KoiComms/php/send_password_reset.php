<?php
include 'database.php';

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
        // For security, always give a generic success message even if email not found
        // to prevent email enumeration.
        $message = "If an admin account with that email exists, a password reset link has been sent.";
        // IMPORTANT: For debugging during setup, you might want to remove the header redirection here
        // so you can see the Mailer Error if it happens before this point.
        header("Location: ../components/forgot_password.php?message=" . urlencode($message));
        exit();
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // Generate a unique token
    $token = bin2hex(random_bytes(32)); // 64 character hex string
    $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

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

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings - USER MUST CONFIGURE THESE!
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;    // Enable verbose debug output (for troubleshooting)
                                                     // Set to 0 in production to disable debug output
            $mail->isSMTP();                          // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';   // Replace with your SMTP server (e.g., 'smtp.gmail.com', 'smtp.mail.yahoo.com')
            $mail->SMTPAuth   = true;                 // Enable SMTP authentication
            $mail->Username   = 'user@example.com';   // Replace with your SMTP username (your full email address)
            $mail->Password   = 'your_password';      // Replace with your SMTP password (or app password for Gmail/Outlook)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use PHPMailer::ENCRYPTION_SMTPS (for SSL/port 465) or PHPMailer::ENCRYPTION_STARTTLS (for TLS/port 587)
            $mail->Port       = 465;                  // TCP port to connect to; 465 for SMTPS, 587 for STARTTLS

            // Recipients
            $mail->setFrom('no-reply@gmail.com', 'Army\'s Angels Integrated School, INC.'); // Replace with your sender email and name
            $mail->addAddress($email);                // Add a recipient (the admin's email)

            // Content
            $mail->isHTML(false);                     // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            $message = "If an admin account with that email exists, a password reset link has been sent.";
            // If email is sent successfully, redirect back to forgot password page
            header("Location: ../components/forgot_password.php?message=" . urlencode($message));
            exit();

        } catch (Exception $e) {
            // Display the error immediately for debugging
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            // Optionally, log the error for production: error_log("Mailer Error: " . $mail->ErrorInfo);
            // DO NOT redirect here during debugging, let the error show on screen.
            exit(); // Stop script execution to show the error.
        }
    } else {
        $message = "Error generating reset link. Please try again later.";
        header("Location: ../components/forgot_password.php?message=" . urlencode($message));
        exit();
    }

    $stmt->close();
    $conn->close();
    // This part of code will only be reached if there was an issue with SQL execution (not mailer)
    // or if the initial email check failed.
    header("Location: ../components/forgot_password.php?message=" . urlencode($message));
    exit();
} else {
    // If accessed via GET request without POST data
    header("Location: ../components/forgot_password.php");
    exit();
}

