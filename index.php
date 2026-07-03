<?php
require_once 'config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduEval - Faculty Performance & Feedback Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    <li><a href="student/dashboard.php">Dashboard</a></li>
                    <li><a href="student/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['student_name']); ?>)</a></li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li><a href="admin/dashboard.php">Admin Panel</a></li>
                    <li><a href="admin/logout.php">Logout (Admin)</a></li>
                <?php else: ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="student/login.php">Student Login</a></li>
                    <li><a href="student/register.php">Student Register</a></li>
                    <li><a href="admin/login.php">Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Welcome to <span class="highlight">EduEval</span></h1>
                    <h2>Faculty Performance & Feedback Portal</h2>
                    <p>Your voice matters! Help us improve the quality of education by providing honest feedback about your faculty members.</p>
                    
                    <?php if(!isset($_SESSION['student_id']) && !isset($_SESSION['admin_id'])): ?>
                        <div class="hero-buttons">
                            <a href="student/login.php" class="btn btn-primary btn-large">Student Login</a>
                            <a href="student/register.php" class="btn btn-secondary btn-large">Register as Student</a>
                            <a href="admin/login.php" class="btn btn-outline btn-large">Admin Access</a>
                        </div>
                    <?php elseif(isset($_SESSION['student_id'])): ?>
                        <div class="hero-buttons">
                            <a href="student/dashboard.php" class="btn btn-primary btn-large">Go to Dashboard</a>
                        </div>
                    <?php elseif(isset($_SESSION['admin_id'])): ?>
                        <div class="hero-buttons">
                            <a href="admin/dashboard.php" class="btn btn-primary btn-large">Go to Admin Panel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <h2 class="section-title">Why Choose EduEval?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📝</div>
                        <h3>Multi-Criteria Ratings</h3>
                        <p>Evaluate faculty on Quality of Material, Punctuality, and Engagement with a 1-5 star rating system.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>Secure & Anonymous</h3>
                        <p>Your feedback is secure. Option to submit anonymously for honest and candid responses.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Real-Time Analytics</h3>
                        <p>Administrators get instant access to performance metrics and sentiment analysis.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🚫</div>
                        <h3>No Duplicate Submissions</h3>
                        <p>Each student can evaluate a faculty member only once, ensuring data integrity.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats">
            <div class="container">
                <div class="stats-grid">
                    <?php
                    require_once 'config/database.php';
                    $pdo = getConnection();
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM faculty");
                    $faculty_count = $stmt->fetch()['total'];
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
                    $student_count = $stmt->fetch()['total'];
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM evaluations");
                    $eval_count = $stmt->fetch()['total'];
                    ?>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $faculty_count; ?></div>
                        <div class="stat-label">Faculty Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $student_count; ?></div>
                        <div class="stat-label">Registered Students</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $eval_count; ?></div>
                        <div class="stat-label">Evaluations Submitted</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Register / Login</h3>
                        <p>Create your student account or login with existing credentials.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Browse Faculty</h3>
                        <p>View faculty members filtered by department.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Submit Evaluation</h3>
                        <p>Rate faculty on multiple criteria and add comments.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>View Analytics</h3>
                        <p>Admins access real-time performance data and insights.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>EduEval</h3>
                    <p>Faculty Performance & Feedback Portal</p>
                    <p>Empowering students, improving education.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="student/login.php">Student Login</a></li>
                        <li><a href="student/register.php">Student Register</a></li>
                        <li><a href="admin/login.php">Admin Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Department of Software Engineering</p>
                    <p>6th Semester Project</p>
                    <p>Syed Muhammad Yousuf & Mehran Ullah</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> EduEval. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>