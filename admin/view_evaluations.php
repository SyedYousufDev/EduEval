<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

$pdo = getConnection();

// Get all evaluations with student and faculty details
$stmt = $pdo->query("
    SELECT 
        e.*,
        s.name as student_name,
        s.roll_no,
        f.name as faculty_name,
        f.designation,
        d.name as department_name
    FROM evaluations e
    JOIN students s ON e.student_id = s.id
    JOIN faculty f ON e.faculty_id = f.id
    JOIN departments d ON f.department_id = d.id
    ORDER BY e.created_at DESC
");
$evaluations = $stmt->fetchAll();

// Get faculty list for filtering
$faculty_list = getAllFaculty();

// Handle filter
$filter_faculty = isset($_GET['faculty_id']) ? $_GET['faculty_id'] : 'all';
if($filter_faculty != 'all') {
    $stmt = $pdo->prepare("
        SELECT 
            e.*,
            s.name as student_name,
            s.roll_no,
            f.name as faculty_name,
            f.designation,
            d.name as department_name
        FROM evaluations e
        JOIN students s ON e.student_id = s.id
        JOIN faculty f ON e.faculty_id = f.id
        JOIN departments d ON f.department_id = d.id
        WHERE e.faculty_id = ?
        ORDER BY e.created_at DESC
    ");
    $stmt->execute([$filter_faculty]);
    $evaluations = $stmt->fetchAll();
}

include '../includes/header.php';
?>

<div class="admin-manage">
    <h2>📋 View All Evaluations</h2>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <h3>Filter by Faculty:</h3>
        <form method="GET" action="" class="filter-form">
            <select name="faculty_id" onchange="this.form.submit()">
                <option value="all">All Faculty Members</option>
                <?php foreach($faculty_list as $faculty): ?>
                    <option value="<?php echo $faculty['id']; ?>" <?php echo $filter_faculty == $faculty['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($faculty['name']); ?> (<?php echo htmlspecialchars($faculty['department_name']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    
    <!-- Statistics Summary -->
    <div class="stats-summary">
        <div class="stat-box">
            <span class="stat-number"><?php echo count($evaluations); ?></span>
            <span class="stat-label">Total Evaluations</span>
        </div>
        <div class="stat-box">
            <span class="stat-number">
                <?php 
                $unique_students = array_unique(array_column($evaluations, 'student_id'));
                echo count($unique_students);
                ?>
            </span>
            <span class="stat-label">Unique Students</span>
        </div>
    </div>
    
    <!-- Evaluations Table -->
    <div class="evaluations-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Faculty</th>
                    <th>Department</th>
                    <th>Quality</th>
                    <th>Punctuality</th>
                    <th>Engagement</th>
                    <th>Overall</th>
                    <th>Sentiment</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($evaluations) > 0): ?>
                    <?php foreach($evaluations as $eval): ?>
                        <?php 
                        $overall = ($eval['quality_material'] + $eval['punctuality'] + $eval['engagement']) / 3;
                        $sentiment = analyzeSentiment($eval['comment']);
                        ?>
                        <tr>
                            <td><?php echo $eval['id']; ?></td>
                            <td><?php echo htmlspecialchars($eval['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($eval['roll_no']); ?></td>
                            <td><strong><?php echo htmlspecialchars($eval['faculty_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($eval['department_name']); ?></td>
                            <td>
                                <div class="rating-badge">
                                    <?php echo $eval['quality_material']; ?>/5
                                    <div class="stars">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php echo $i <= $eval['quality_material'] ? '★' : '☆'; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="rating-badge">
                                    <?php echo $eval['punctuality']; ?>/5
                                    <div class="stars">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php echo $i <= $eval['punctuality'] ? '★' : '☆'; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="rating-badge">
                                    <?php echo $eval['engagement']; ?>/5
                                    <div class="stars">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php echo $i <= $eval['engagement'] ? '★' : '☆'; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="overall-score">
                                <?php echo number_format($overall, 1); ?>/5
                            </td>
                            <td>
                                <span class="sentiment <?php echo strtolower(str_replace(' ', '-', $sentiment)); ?>">
                                    <?php echo $sentiment; ?>
                                </span>
                            </td>
                            <td class="comment-cell">
                                <?php echo !empty($eval['comment']) ? htmlspecialchars(substr($eval['comment'], 0, 100)) . (strlen($eval['comment']) > 100 ? '...' : '') : '<em>No comment</em>'; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($eval['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" style="text-align: center;">No evaluations found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>