<?php
// check-attendance.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Include database connection
require_once('../conn/conn.php');

// Function to log errors
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, '../logs/error.log');
}

try {
    if (!isset($_POST['qr_code'])) {
        throw new Exception('No QR code provided');
    }

    $qr_code = trim($_POST['qr_code']);
    
    if (empty($qr_code)) {
        throw new Exception('QR code cannot be empty');
    }

    // Get current date in Manila timezone
    date_default_timezone_set('Asia/Manila');
    $today = date('Y-m-d');
    
    // Debug log
    logError("Checking QR code: " . $qr_code . " for date: " . $today);

    // First check if student exists
    $studentQuery = "SELECT tbl_student_id, student_name FROM tbl_student WHERE qr_code = :qr_code";
    $studentStmt = $conn->prepare($studentQuery);
    $studentStmt->bindParam(':qr_code', $qr_code);
    
    if (!$studentStmt->execute()) {
        throw new Exception('Failed to execute student query: ' . implode(' ', $studentStmt->errorInfo()));
    }
    
    $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Student not found with provided QR code'
        ]);
        exit;
    }

    // Check for existing attendance
    $attendanceQuery = "SELECT COUNT(*) FROM tbl_attendance 
                       WHERE tbl_student_id = :student_id 
                       AND DATE(time_in) = :today";
    
    $attendanceStmt = $conn->prepare($attendanceQuery);
    $attendanceStmt->bindParam(':student_id', $student['tbl_student_id']);
    $attendanceStmt->bindParam(':today', $today);
    
    if (!$attendanceStmt->execute()) {
        throw new Exception('Failed to execute attendance query: ' . implode(' ', $attendanceStmt->errorInfo()));
    }
    
    $hasAttendance = $attendanceStmt->fetchColumn() > 0;

    if ($hasAttendance) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Attendance already recorded for ' . $student['student_name']
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Attendance can be recorded',
            'student_name' => $student['student_name'],
            'student_id' => $student['tbl_student_id']
        ]);
    }

} catch (PDOException $e) {
    logError("Database Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    logError("General Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>