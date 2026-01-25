<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: enrolleeside.php');
    exit;
}

// Include database connection to check for existing ID
include '../php/database.php';
$userId = $_SESSION['user_id'];
$existingEnrollmentId = null;

$sql_check = "SELECT enrollment_id FROM users WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $userId);
$stmt_check->execute();
$result = $stmt_check->get_result();
$user = $result->fetch_assoc();
if ($user && $user['enrollment_id']) {
    $existingEnrollmentId = $user['enrollment_id'];
}
$stmt_check->close();
$conn->close();

$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User';
$formUrl = "https://docs.google.com/forms/d/e/1FAIpQLSfgqKHwYmDm2FPWLBCyHL0awb6zPHps4rwwPDKNpnRU3maDSA/viewform";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollee Dashboard - AAIS</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f4f4; }
        .dashboard-wrapper { padding-top: 120px; padding-bottom: 80px; min-height: 80vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .dashboard-container { text-align: center; width: 100%; max-width: 900px; padding: 20px; }
        .header-title { font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #555; margin-bottom: 10px; }
        .greeting { font-size: 2.5rem; font-weight: 800; margin-bottom: 40px; color: #333; }
        
        .enrollment-status { padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.07); }
        .enrollment-id-display { font-size: 1.5rem; font-weight: 600; color: var(--dark-green); }
        .enrollment-id-display span { font-family: 'Courier New', Courier, monospace; background: #e9e9e9; padding: 5px 10px; border-radius: 5px; color: #333; }

        .dash-btn {
            display: inline-block;
            text-decoration: none;
            color: var(--white);
            font-weight: 700;
            font-size: 1.1rem;
            padding: 15px 40px;
            background-color: var(--bright-green);
            border-radius: 50px; 
            transition: all 0.3s ease;
        }
        .dash-btn:hover { background-color: var(--leaf-green); transform: translateY(-3px); }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="../assets/logo.jpeg" alt="AAIS Logo" class="logo">
            <div class="brand-text">
                <span class="school-name">Army's Angels Integrated School Inc</span>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="homepage.html">Home</a></li>
            <li><a href="../php/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <div class="dashboard-wrapper">
        <div class="dashboard-container">
            <div class="header-title">Enrollee Dashboard</div>
            <h1 class="greeting">Welcome, <?php echo $userName; ?>!</h1>

            <div class="enrollment-status" id="enrollment-container">
                <?php if ($existingEnrollmentId): ?>
                    <h3>Your Enrollment ID is:</h3>
                    <p class="enrollment-id-display"><span><?php echo htmlspecialchars($existingEnrollmentId); ?></span></p>
                <?php else: ?>
                    <h3>Ready to Enroll?</h3>
                    <p style="margin-bottom: 25px; color: #666;">Click the button below to fill out the official enrollment form.</p>
                    <a href="<?php echo $formUrl; ?>" class="dash-btn" id="apply-btn" target="_blank">Apply Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer id="contact">
        <!-- Footer content can be added back if needed -->
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const applyBtn = document.getElementById('apply-btn');
        const formUrl = '<?php echo $formUrl; ?>';
        const enrollmentContainer = document.getElementById('enrollment-container');

        // When "Apply Now" is clicked, mark that the user has visited the form
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                sessionStorage.setItem('formVisited', 'true');
            });
        }

        // When the user comes back to this page, check if they visited the form
        if (sessionStorage.getItem('formVisited') === 'true') {
            // Use a small timeout to let the user see the page before the prompt
            setTimeout(() => {
                const isFinished = confirm("Have you finished filling out the enrollment form?");
                
                if (isFinished) {
                    // User confirmed, generate the ID
                    generateEnrollmentId();
                } else {
                    // User has not finished, send them back to the form
                    window.location.href = formUrl;
                }
                // Clear the flag
                sessionStorage.removeItem('formVisited');
            }, 500);
        }

        function generateEnrollmentId() {
            enrollmentContainer.innerHTML = '<p>Generating your Enrollment ID, please wait...</p>';

            fetch('../php/generate_id.php', {
                method: 'POST'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.enrollment_id) {
                    enrollmentContainer.innerHTML = `
                        <h3>Your Enrollment ID is:</h3>
                        <p class="enrollment-id-display"><span>${data.enrollment_id}</span></p>
                        <p style="margin-top: 20px; font-size: 0.9rem; color: #555;">Please save this ID for your records.</p>
                    `;
                } else {
                    throw new Error(data.error || 'Unknown error occurred.');
                }
            })
            .catch(error => {
                console.error('Error generating ID:', error);
                enrollmentContainer.innerHTML = `<p style="color: red;">Could not generate Enrollment ID. Please try again later or contact support.</p>`;
            });
        }
    });
    </script>

</body>
</html>