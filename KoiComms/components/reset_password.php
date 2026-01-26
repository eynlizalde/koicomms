<?php
include '../php/database.php';

$token = $_GET['token'] ?? '';
$message = '';

if (empty($token)) {
    $message = 'No reset token provided.';
} else {
    $sql = "SELECT id, reset_token_expires_at FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = 'Invalid or expired reset token.';
    } else {
        $user = $result->fetch_assoc();
        if (new DateTime() > new DateTime($user['reset_token_expires_at'])) {
            $message = 'Invalid or expired reset token.';
        }
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($message)) {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = 'Please enter and confirm your new password.';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match.';
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE reset_token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $token);

        if ($stmt->execute()) {
            $message = 'Your password has been successfully reset. You can now log in.';
            header("Location: adminside.html?message=" . urlencode($message));
            exit();
        } else {
            $message = 'Error resetting password: ' . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Admin</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--leaf-green) 100%);
        }
        .reset-password-container {
            background-color: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .reset-password-container h2 {
            color: var(--dark-green);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: var(--medium-green);
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
        }
        .btn-submit {
            background-color: var(--bright-green);
            color: var(--white);
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: var(--leaf-green);
        }
        .message {
            margin-top: 20px;
            font-weight: 500;
        }
        .message.success {
            color: var(--bright-green);
        }
        .message.error {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo (strpos($message, 'successfully') !== false || strpos($message, 'now log in') !== false) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if (empty($message) || (strpos($message, 'Invalid or expired reset token.') === false && strpos($message, 'No reset token provided.') === false)): ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-submit">Reset Password</button>
            </form>
        <?php endif; ?>
        <p style="margin-top: 20px;"><a href="adminside.html" style="color: var(--medium-green);">Back to Login</a></p>
    </div>
</body>
</html>
