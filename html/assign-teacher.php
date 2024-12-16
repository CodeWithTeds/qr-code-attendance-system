<?php
include('./conn/conn.php');
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $studentId = $_POST['student_id'];
        $teacherId = $_POST['teacher_id'];

        // Remove existing assignments for this student
        $deleteStmt = $conn->prepare("DELETE FROM user_student_mapping WHERE student_id = :student_id");
        $deleteStmt->bindParam(':student_id', $studentId);
        $deleteStmt->execute();

        // Add new assignment
        $insertStmt = $conn->prepare("INSERT INTO user_student_mapping (student_id, teacher_id) VALUES (:student_id, :teacher_id)");
        $insertStmt->bindParam(':student_id', $studentId);
        $insertStmt->bindParam(':teacher_id', $teacherId);
        $insertStmt->execute();

        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>