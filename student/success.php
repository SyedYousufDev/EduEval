<?php
require_once '../config/session.php';
redirectIfNotStudent();

$faculty_name = isset($_GET['faculty']) ? $_GET['faculty'] : 'the faculty member';
include '../includes/header.php';
?>

<div class="success-page">
    <div class="success-card">
        <div class="success-icon">✓</div>
        <h2>Thank You for Your Feedback!</h2>
        <p>Your evaluation for <strong><?php echo htmlspecialchars($faculty_name); ?></strong> has been submitted successfully.</p>
        <p>Your feedback helps us improve the quality of education.</p>
        <a href="dashboard.php" class="btn btn-primary">Evaluate Another Faculty</a>
        <a href="../index.php" class="btn btn-secondary">Go to Home</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>