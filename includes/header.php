<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduEval - Faculty Performance Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <h1>📊 EduEval</h1>
                <span>Faculty Performance Portal</span>
            </div>
            <ul class="nav-links">
                <?php if(isset($_SESSION['student_id'])): ?>
                    <li><a href="../student/dashboard.php">Dashboard</a></li>
                    <li><a href="../student/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['student_name']); ?>)</a></li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li><a href="../admin/dashboard.php">Admin Panel</a></li>
                    <li><a href="../admin/faculty_manage.php">Manage Faculty</a></li>
                    <li><a href="../admin/logout.php">Logout (Admin)</a></li>
                <?php else: ?>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../student/login.php">Student Login</a></li>
                    <li><a href="../student/register.php">Student Register</a></li>
                    <li><a href="../admin/login.php">Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">