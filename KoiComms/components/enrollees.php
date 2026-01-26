<?php
session_start();

// If user is not logged in or is not an admin, redirect to admin login page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: adminside.php?error=Please log in as an admin to view this page.');
    exit;
}

include '../php/database.php';

// Fetch all users who have an enrollment ID
$sql = "SELECT id, name, email, enrollment_id FROM users WHERE enrollment_id IS NOT NULL ORDER BY reg_date DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Users - Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; }
        .page-wrapper { padding-top: 100px; padding-bottom: 60px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .page-title { font-size: 2rem; color: var(--dark-green); margin-bottom: 20px; border-bottom: 3px solid var(--light-green); padding-bottom: 10px; }
        
        .enrollees-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .enrollees-table th, .enrollees-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .enrollees-table th {
            background-color: var(--medium-green);
            color: white;
            font-weight: 600;
        }
        .enrollees-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .enrollees-table tr:hover {
            background-color: #f1f1f1;
        }
        .no-enrollees {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #777;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="../assets/logo.jpeg" alt="AAIS Logo" class="logo">
            <div class="brand-text"><span class="school-name">Army's Angels Integrated School, INC.</span></div>
        </div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="../php/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <div class="page-wrapper">
        <div class="container">
            <h1 class="page-title">Enrolled Users List</h1>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="enrollees-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Enrollment ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['enrollment_id']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-enrollees">No users have completed enrollment yet.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
<?php
$conn->close();
?>