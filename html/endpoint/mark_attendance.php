<?php
include ('../conn/conn.php');
date_default_timezone_set('Asia/Manila');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $studentID = $_POST['student_id'];

    // Check if the student is already marked as present today
    $query = "SELECT * FROM tbl_attendance WHERE tbl_student_id = :student_id AND DATE(time_in) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $studentID);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        // Mark the student as present
        $insertQuery = "INSERT INTO tbl_attendance (tbl_student_id, time_in) VALUES (:student_id, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':student_id', $studentID);
        if ($insertStmt->execute()) {
            $response['success'] = true;
            $response['time_in'] = (new DateTime())->format("M d, Y g:i A");
        } else {
            $response['error'] = 'Database error: Unable to mark attendance.';
        }
    } else {
        $response['error'] = 'Attendance already marked for today.';
    }
} else {
    $response['error'] = 'Invalid request.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
    