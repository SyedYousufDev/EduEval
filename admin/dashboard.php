<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

// Get statistics
$pdo = getConnection();

// Total students
$stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
$total_students = $stmt->fetch()['total'];

// Total faculty
$stmt = $pdo->query("SELECT COUNT(*) as total FROM faculty");
$total_faculty = $stmt->fetch()['total'];

// Total evaluations
$stmt = $pdo->query("SELECT COUNT(*) as total FROM evaluations");
$total_evaluations = $stmt->fetch()['total'];

// Average ratings
$stmt = $pdo->query("
    SELECT 
        ROUND(AVG(quality_material), 2) as avg_quality,
        ROUND(AVG(punctuality), 2) as avg_punctuality,
        ROUND(AVG(engagement), 2) as avg_engagement
    FROM evaluations
");
$avg_ratings = $stmt->fetch();

$faculty_scores = getAllFacultyWithScores();

include '../includes/header.php';
?>

<div class="admin-dashboard">
    <h2>Admin Power-Panel Dashboard</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_students; ?></div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_faculty; ?></div>
            <div class="stat-label">Total Faculty</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_evaluations; ?></div>
            <div class="stat-label">Total Evaluations</div>
        </div>
    </div>
    
    <div class="avg-ratings">
        <h3>Overall Average Ratings (All Faculty)</h3>
        <div class="rating-bars">
            <div class="rating-item">
                <span>Quality of Material:</span>
                <div class="bar">
                    <div class="bar-fill" style="width: <?php echo ($avg_ratings['avg_quality']/5)*100; ?>%"></div>
                </div>
                <span class="rating-value"><?php echo $avg_ratings['avg_quality']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>Punctuality:</span>
                <div class="bar">
                    <div class="bar-fill" style="width: <?php echo ($avg_ratings['avg_punctuality']/5)*100; ?>%"></div>
                </div>
                <span class="rating-value"><?php echo $avg_ratings['avg_punctuality']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>Engagement:</span>
                <div class="bar">
                    <div class="bar-fill" style="width: <?php echo ($avg_ratings['avg_engagement']/5)*100; ?>%"></div>
                </div>
                <span class="rating-value"><?php echo $avg_ratings['avg_engagement']; ?>/5</span>
            </div>
        </div>
    </div>
    
    <div class="faculty-performance">
        <h3>Faculty Performance Rankings</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Faculty Name</th>
                    <th>Department</th>
                    <th>Quality</th>
                    <th>Punctuality</th>
                    <th>Engagement</th>
                    <th>Overall</th>
                    <th>Evaluations</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                foreach($faculty_scores as $faculty): 
                ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><strong><?php echo htmlspecialchars($faculty['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($faculty['department_name']); ?></td>
                    <td><?php echo $faculty['avg_quality'] ?: 'N/A'; ?></td>
                    <td><?php echo $faculty['avg_punctuality'] ?: 'N/A'; ?></td>
                    <td><?php echo $faculty['avg_engagement'] ?: 'N/A'; ?></td>
                    <td class="overall-score"><?php echo $faculty['overall_score'] ?: 'N/A'; ?></td>
                    <td><?php echo $faculty['evaluation_count']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>