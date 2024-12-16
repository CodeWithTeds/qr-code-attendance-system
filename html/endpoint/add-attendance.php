<?php
include('../conn/conn.php');
session_start();
date_default_timezone_set('Asia/Manila');

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_code'])) {
    $qrCode = $_POST['qr_code'];

    try {
        // First, get the student ID from the QR code
        $selectStmt = $conn->prepare("SELECT tbl_student_id FROM tbl_student WHERE generated_code = :generated_code");
        $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);
        $selectStmt->execute();

        $result = $selectStmt->fetch();

        if ($result) {
            $studentId = $result["tbl_student_id"];
            $currentDate = date('Y-m-d');

            // Check if student has already attended today
            $checkQuery = "SELECT COUNT(*) FROM tbl_attendance 
                          WHERE tbl_student_id = :student_id 
                          AND DATE(time_in) = :current_date";

            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $checkStmt->bindParam(':current_date', $currentDate, PDO::PARAM_STR);
            $checkStmt->execute();

            $hasAttended = $checkStmt->fetchColumn() > 0;

            if ($hasAttended) {
                $response = ['status' => 'warning', 'message' => 'Attendance already recorded for today.'];
            } else {
                // Record new attendance
                $timeIn = date("Y-m-d H:i:s");
                $insertQuery = "INSERT INTO tbl_attendance (tbl_student_id, time_in) 
                               VALUES (:student_id, :time_in)";

                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
                $insertStmt->bindParam(':time_in', $timeIn, PDO::PARAM_STR);

                if ($insertStmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Attendance recorded successfully.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to record attendance.'];
                }   
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid QR Code. No student found.'];
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Please scan a valid QR code.'];
}

// Return the response as JSON
echo json_encode($response);
exit();
