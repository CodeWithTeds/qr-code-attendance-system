<?php
// endpoint/get-teachers.php
include('../conn/conn.php');

header('Content-Type: application/json');

if(isset($_GET['course'])) {
    try {
        $stmt = $conn->prepare("SELECT teacher_id, teacher_name FROM tbl_teachers WHERE course = :course ORDER BY teacher_name ASC");
        $stmt->bindParam(':course', $_GET['course']);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($teachers);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Course parameter is required']);
}