<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotStudent();

$department_id = isset($_GET['dept']) ? $_GET['dept'] : 'all';
$faculty_list = [];

if($department_id == 'all') {
    $faculty_list = getAllFaculty();
} else {
    $faculty_list = getFacultyByDepartment($department_id);
}

$departments = getDepartments();
include '../includes/header.php';
?>

<div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!</h2>
    <p>Select a faculty member to evaluate their performance.</p>
    
    <div class="filter-section">
        <h3>Filter by Department:</h3>
        <div class="filter-buttons">
            <a href="?dept=all" class="btn <?php echo $department_id == 'all' ? 'btn-active' : 'btn-secondary'; ?>">All Departments</a>
            <?php foreach($departments as $dept): ?>
                <a href="?dept=<?php echo $dept['id']; ?>" class="btn <?php echo $department_id == $dept['id'] ? 'btn-active' : 'btn-secondary'; ?>">
                    <?php echo htmlspecialchars($dept['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="faculty-grid">
        <?php foreach($faculty_list as $faculty): ?>
            <div class="faculty-card">
                <h3><?php echo htmlspecialchars($faculty['name']); ?></h3>
                <p class="designation"><?php echo htmlspecialchars($faculty['designation']); ?></p>
                <p class="department">Department: <?php echo htmlspecialchars($faculty['department_name']); ?></p>
                <p class="email">Email: <?php echo htmlspecialchars($faculty['email']); ?></p>
                <a href="evaluate.php?id=<?php echo $faculty['id']; ?>" class="btn btn-primary">Evaluate Faculty</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>