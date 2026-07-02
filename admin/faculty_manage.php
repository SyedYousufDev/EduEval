<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

redirectIfNotAdmin();

$pdo = getConnection();
$message = '';

// Add new faculty
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faculty'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $department_id = $_POST['department_id'];
    $designation = sanitizeInput($_POST['designation']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO faculty (name, email, department_id, designation) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $department_id, $designation]);
        $message = "Faculty member added successfully!";
    } catch(PDOException $e) {
        $message = "Error: Email already exists!";
    }
}

// Delete faculty
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM faculty WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "Faculty member deleted!";
}

$faculty_list = getAllFaculty();
$departments = getDepartments();

include '../includes/header.php';
?>

<div class="admin-manage">
    <h2>Manage Faculty</h2>
    
    <?php if($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="add-form">
        <h3>Add New Faculty Member</h3>
        <form method="POST" action="">
            <div class="form-row">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="department_id" required>
                    <option value="">Select Department</option>
                    <?php foreach($departments as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="designation" placeholder="Designation (e.g., Professor, Lecturer)">
                <button type="submit" name="add_faculty" class="btn btn-primary">Add Faculty</button>
            </div>
        </form>
    </div>
    
    <div class="faculty-list">
        <h3>Existing Faculty Members</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($faculty_list as $faculty): ?>
                <tr>
                    <td><?php echo $faculty['id']; ?></td>
                    <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                    <td><?php echo htmlspecialchars($faculty['email']); ?></td>
                    <td><?php echo htmlspecialchars($faculty['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($faculty['designation']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $faculty['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>