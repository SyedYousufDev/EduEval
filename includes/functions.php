<?php
require_once __DIR__ . '/../config/database.php';

function hasStudentEvaluatedFaculty($student_id, $faculty_id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM evaluations WHERE student_id = ? AND faculty_id = ?");
    $stmt->execute([$student_id, $faculty_id]);
    return $stmt->fetchColumn() > 0;
}

function getFacultyAverageScore($faculty_id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT 
            AVG(quality_material) as avg_quality,
            AVG(punctuality) as avg_punctuality,
            AVG(engagement) as avg_engagement,
            COUNT(*) as total_evaluations,
            (AVG(quality_material) + AVG(punctuality) + AVG(engagement)) / 3 as overall_avg
        FROM evaluations 
        WHERE faculty_id = ?
    ");
    $stmt->execute([$faculty_id]);
    return $stmt->fetch();
}

function getAllFacultyWithScores() {
    $pdo = getConnection();
    $stmt = $pdo->query("
        SELECT 
            f.*,
            d.name as department_name,
            COUNT(e.id) as evaluation_count,
            ROUND(AVG(e.quality_material), 2) as avg_quality,
            ROUND(AVG(e.punctuality), 2) as avg_punctuality,
            ROUND(AVG(e.engagement), 2) as avg_engagement,
            ROUND((AVG(e.quality_material) + AVG(e.punctuality) + AVG(e.engagement)) / 3, 2) as overall_score
        FROM faculty f
        LEFT JOIN departments d ON f.department_id = d.id
        LEFT JOIN evaluations e ON f.id = e.faculty_id
        GROUP BY f.id
        ORDER BY overall_score DESC
    ");
    return $stmt->fetchAll();
}

function getDepartments() {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM departments ORDER BY name");
    return $stmt->fetchAll();
}

function getFacultyByDepartment($department_id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT f.*, d.name as department_name 
        FROM faculty f
        JOIN departments d ON f.department_id = d.id
        WHERE f.department_id = ?
        ORDER BY f.name
    ");
    $stmt->execute([$department_id]);
    return $stmt->fetchAll();
}

function getAllFaculty() {
    $pdo = getConnection();
    $stmt = $pdo->query("
        SELECT f.*, d.name as department_name 
        FROM faculty f
        JOIN departments d ON f.department_id = d.id
        ORDER BY f.name
    ");
    return $stmt->fetchAll();
}

function analyzeSentiment($comment) {
    if(empty($comment)) return "No comment";
    
    $positive_words = ['good', 'great', 'excellent', 'amazing', 'helpful', 'clear', 'nice', 'best', 'wonderful', 'outstanding'];
    $negative_words = ['bad', 'poor', 'terrible', 'awful', 'boring', 'confusing', 'worst', 'difficult', 'unclear'];
    
    $comment_lower = strtolower($comment);
    $positive_count = 0;
    $negative_count = 0;
    
    foreach($positive_words as $word) {
        $positive_count += substr_count($comment_lower, $word);
    }
    foreach($negative_words as $word) {
        $negative_count += substr_count($comment_lower, $word);
    }
    
    if($positive_count > $negative_count) return "Positive 😊";
    if($negative_count > $positive_count) return "Negative 😞";
    return "Neutral 😐";
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>