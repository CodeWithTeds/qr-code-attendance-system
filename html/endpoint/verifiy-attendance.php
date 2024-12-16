<?php
// verify-attendance.php
// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow AJAX requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['qr_code'])) {
        $qr_code = $_POST['qr_code'];
        
        // Get current date in Manila timezone
        date_default_timezone_set('Asia/Manila');
        $today = date('Y-m-d');
        
        try {
            // First check if the student exists
            $studentStmt = $conn->prepare("
                SELECT tbl_student_id, student_name 
                FROM tbl_student 
                WHERE qr_code = :qr_code
            ");
            $studentStmt->bindParam(':qr_code', $qr_code);
            $studentStmt->execute();
            
            if ($studentStmt->rowCount() === 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid QR Code: Student not found'
                ]);
                exit;
            }
            
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            
            // Check for existing attendance
            $attendanceStmt = $conn->prepare("
                SELECT tbl_attendance_id 
                FROM tbl_attendance 
                WHERE tbl_student_id = :student_id 
                AND DATE(time_in) = :today
            ");
            
            $attendanceStmt->bindParam(':student_id', $student['tbl_student_id']);
            $attendanceStmt->bindParam(':today', $today);
            $attendanceStmt->execute();
            
            if ($attendanceStmt->rowCount() > 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Attendance already recorded for ' . $student['student_name']
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proceed with attendance',
                    'student_name' => $student['student_name']
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No QR code provided'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>