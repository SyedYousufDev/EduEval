<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

$pdo = getConnection();
$message = '';
$error = '';

// Add new department
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_department'])) {
    $name = sanitizeInput($_POST['name']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->execute([$name]);
        $message = "Department added successfully!";
    } catch(PDOException $e) {
        $error = "Department name already exists!";
    }
}

// Delete department
if(isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM departments WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Department deleted successfully!";
    } catch(PDOException $e) {
        $error = "Cannot delete department with existing faculty members!";
    }
}

// Update department
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_department'])) {
    $id = $_POST['dept_id'];
    $name = sanitizeInput($_POST['name']);
    
    $stmt = $pdo->prepare("UPDATE departments SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);
    $message = "Department updated successfully!";
}

// Get all departments with faculty count
$stmt = $pdo->query("
    SELECT d.*, COUNT(f.id) as faculty_count 
    FROM departments d
    LEFT JOIN faculty f ON d.id = f.department_id
    GROUP BY d.id
    ORDER BY d.name
");
$departments = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="admin-manage">
    <h2>🏢 Manage Departments</h2>
    
    <?php if($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Add Department Form -->
    <div class="add-form">
        <h3>Add New Department</h3>
        <form method="POST" action="">
            <div class="form-row">
                <input type="text" name="name" placeholder="Department Name (e.g., Computer Science)" required>
                <button type="submit" name="add_department" class="btn btn-primary">Add Department</button>
            </div>
        </form>
    </div>
    
    <!-- Departments List -->
    <div class="departments-list">
        <h3>Existing Departments</h3>
        <div class="departments-grid">
            <?php foreach($departments as $dept): ?>
                <div class="department-card">
                    <div class="dept-header">
                        <h4><?php echo htmlspecialchars($dept['name']); ?></h4>
                        <span class="faculty-count"><?php echo $dept['faculty_count']; ?> Faculty</span>
                    </div>
                    <div class="dept-actions">
                        <button onclick="showEditModal(<?php echo $dept['id']; ?>, '<?php echo htmlspecialchars($dept['name']); ?>')" class="btn-edit">Edit</button>
                        <a href="?delete=<?php echo $dept['id']; ?>" class="btn-delete" onclick="return confirm('Delete this department? All faculty in this department will lose department assignment.')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Edit Department</h3>
        <form method="POST" action="">
            <input type="hidden" name="dept_id" id="edit_dept_id">
            <div class="form-group">
                <label>Department Name:</label>
                <input type="text" name="name" id="edit_dept_name" required>
            </div>
            <button type="submit" name="update_department" class="btn btn-primary">Update Department</button>
        </form>
    </div>
</div>

<script>
function showEditModal(id, name) {
    document.getElementById('edit_dept_id').value = id;
    document.getElementById('edit_dept_name').value = name;
    document.getElementById('editModal').style.display = 'block';
}

var modal = document.getElementById('editModal');
var span = document.getElementsByClassName('close')[0];
span.onclick = function() {
    modal.style.display = 'none';
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php include '../includes/footer.php'; ?>