<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Admin</title>
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
        .forgot-password-container {
            background-color: var(--white);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .forgot-password-container h2 {
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
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <p>Enter your email address to receive a password reset link.</p>
        <form action="../php/send_password_reset.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>
        <?php if (isset($_GET['message'])): ?>
            <p class="message <?php echo strpos($_GET['message'], 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </p>
        <?php endif; ?>
        <p style="margin-top: 20px;"><a href="adminside.html" style="color: var(--medium-green);">Back to Login</a></p>
    </div>
</body>
</html>