 <?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotStudent();

$faculty_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Get faculty details
$pdo = getConnection();
$stmt = $pdo->prepare("SELECT f.*, d.name as department_name FROM faculty f JOIN departments d ON f.department_id = d.id WHERE f.id = ?");
$stmt->execute([$faculty_id]);
$faculty = $stmt->fetch();

if(!$faculty) {
    header("Location: dashboard.php");
    exit();
}

// Check for duplicate submission
if(hasStudentEvaluatedFaculty($_SESSION['student_id'], $faculty_id)) {
    $_SESSION['error'] = "You have already evaluated this faculty member!";
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quality_material = $_POST['quality_material'];
    $punctuality = $_POST['punctuality'];
    $engagement = $_POST['engagement'];
    $comment = sanitizeInput($_POST['comment']);
    
    $stmt = $pdo->prepare("INSERT INTO evaluations (student_id, faculty_id, quality_material, punctuality, engagement, comment) VALUES (?, ?, ?, ?, ?, ?)");
    
    if($stmt->execute([$_SESSION['student_id'], $faculty_id, $quality_material, $punctuality, $engagement, $comment])) {
        header("Location: success.php?faculty=" . urlencode($faculty['name']));
        exit();
    } else {
        $error = "Error submitting evaluation. Please try again.";
    }
}

include '../includes/header.php';
?>

<div class="evaluation-form">
    <h2>Evaluate: <?php echo htmlspecialchars($faculty['name']); ?></h2>
    <p class="faculty-info">Department: <?php echo htmlspecialchars($faculty['department_name']); ?></p>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" id="evaluationForm">
        
        <!-- Quality of Material Rating -->
        <div class="rating-section">
            <h3>Quality of Material</h3>
            <div class="star-rating">
                <div class="stars-container" data-rating-group="quality">
                    <input type="radio" name="quality_material" value="5" id="quality_5" required>
                    <label for="quality_5" class="star">★</label>
                    
                    <input type="radio" name="quality_material" value="4" id="quality_4" required>
                    <label for="quality_4" class="star">★</label>
                    
                    <input type="radio" name="quality_material" value="3" id="quality_3" required>
                    <label for="quality_3" class="star">★</label>
                    
                    <input type="radio" name="quality_material" value="2" id="quality_2" required>
                    <label for="quality_2" class="star">★</label>
                    
                    <input type="radio" name="quality_material" value="1" id="quality_1" required>
                    <label for="quality_1" class="star">★</label>
                </div>
                <div class="rating-labels">
                    <span>Poor</span>
                    <span>Fair</span>
                    <span>Good</span>
                    <span>Very Good</span>
                    <span>Excellent</span>
                </div>
            </div>
        </div>
        
        <!-- Punctuality Rating -->
        <div class="rating-section">
            <h3>Punctuality</h3>
            <div class="star-rating">
                <div class="stars-container" data-rating-group="punctuality">
                    <input type="radio" name="punctuality" value="5" id="punctuality_5" required>
                    <label for="punctuality_5" class="star">★</label>
                    
                    <input type="radio" name="punctuality" value="4" id="punctuality_4" required>
                    <label for="punctuality_4" class="star">★</label>
                    
                    <input type="radio" name="punctuality" value="3" id="punctuality_3" required>
                    <label for="punctuality_3" class="star">★</label>
                    
                    <input type="radio" name="punctuality" value="2" id="punctuality_2" required>
                    <label for="punctuality_2" class="star">★</label>
                    
                    <input type="radio" name="punctuality" value="1" id="punctuality_1" required>
                    <label for="punctuality_1" class="star">★</label>
                </div>
                <div class="rating-labels">
                    <span>Poor</span>
                    <span>Fair</span>
                    <span>Good</span>
                    <span>Very Good</span>
                    <span>Excellent</span>
                </div>
            </div>
        </div>
        
        <!-- Engagement Rating -->
        <div class="rating-section">
            <h3>Engagement</h3>
            <div class="star-rating">
                <div class="stars-container" data-rating-group="engagement">
                    <input type="radio" name="engagement" value="5" id="engagement_5" required>
                    <label for="engagement_5" class="star">★</label>
                    
                    <input type="radio" name="engagement" value="4" id="engagement_4" required>
                    <label for="engagement_4" class="star">★</label>
                    
                    <input type="radio" name="engagement" value="3" id="engagement_3" required>
                    <label for="engagement_3" class="star">★</label>
                    
                    <input type="radio" name="engagement" value="2" id="engagement_2" required>
                    <label for="engagement_2" class="star">★</label>
                    
                    <input type="radio" name="engagement" value="1" id="engagement_1" required>
                    <label for="engagement_1" class="star">★</label>
                </div>
                <div class="rating-labels">
                    <span>Poor</span>
                    <span>Fair</span>
                    <span>Good</span>
                    <span>Very Good</span>
                    <span>Excellent</span>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Additional Comments (Optional):</label>
            <textarea name="comment" rows="4" placeholder="Share your feedback about this faculty member..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Evaluation</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>