<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $roll_no = sanitizeInput($_POST['roll_no']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department_id = $_POST['department_id'];
    
    if($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $pdo = getConnection();
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, email, roll_no, password, department_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $roll_no, $hashed_password, $department_id]);
            $success = "Registration successful! Please login.";
        } catch(PDOException $e) {
            $error = "Email or Roll Number already exists!";
        }
    }
}

$departments = getDepartments();
include '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Student Registration</h2>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?> <a href="login.php">Login here</a></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Roll Number:</label>
                <input type="text" name="roll_no" required>
            </div>
            <div class="form-group">
                <label>Department:</label>
                <select name="department_id" required>
                    <option value="">Select Department</option>
                    <?php foreach($departments as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>