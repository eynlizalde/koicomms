<?php
// SMTP Configuration for PHPMailer
// ---------------------------------
// IMPORTANT: FILL IN THESE DETAILS WITH YOUR EMAIL PROVIDER'S SMTP SETTINGS

// The address of your SMTP server.
// Examples: 'smtp.gmail.com' for Gmail, 'smtp.office365.com' for Outlook.
define('SMTP_HOST', 'smtp.gmail.com');

// Your full email address.
define('SMTP_USERNAME', 'armysangelsw@gmail.com');

// Your email password.
// *** IMPORTANT: If you use Gmail with 2-Factor Authentication (2FA),
// you MUST generate an "App Password" and use it here. Your regular password will not work.
define('SMTP_PASSWORD', 'webAAISinc');

// The TCP port to connect to.
// Use 465 for `ENCRYPTION_SMTPS` (SSL) OR 587 for `ENCRYPTION_STARTTLS` (TLS).
define('SMTP_PORT', 465);

// The encryption method to use.
// 'ssl' (for `ENCRYPTION_SMTPS`) is recommended.
// Or use 'tls' (for `ENCRYPTION_STARTTLS`).
define('SMTP_SECURE', 'ssl');

// The "From" email address that will appear on the email.
define('SMTP_FROM_EMAIL', 'aaisresetpassword@gmail.com');

// The "From" name that will appear on the email.
define('SMTP_FROM_NAME', "Army's Angels Integrated School");