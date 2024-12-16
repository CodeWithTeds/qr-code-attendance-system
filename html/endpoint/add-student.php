<?php
// Include database connection
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate that all required fields are present
    $requiredFields = ['student_id', 'student_name', 'course', 'section', 'generated_code'];
    $missingFields = false;
    
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $missingFields = true;
            break;
        }
    }

    if ($missingFields) {
        echo "<script>
                alert('Please fill in all required fields!');
                window.location.href = '../html/attendance_admin.php';
              </script>";
        exit();
    }

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Sanitize inputs
        $studentId = filter_var($_POST['student_id'], FILTER_SANITIZE_NUMBER_INT);
        $studentName = filter_var($_POST['student_name'], FILTER_SANITIZE_STRING);
        $course = filter_var($_POST['course'], FILTER_SANITIZE_STRING);
        $section = filter_var($_POST['section'], FILTER_SANITIZE_STRING);
        $generatedCode = filter_var($_POST['generated_code'], FILTER_SANITIZE_STRING);

        // Check if student ID already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_student WHERE tbl_student_id = :student_id");
        $checkStmt->bindParam(":student_id", $studentId, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception("Student ID already exists!");
        }

        // Insert new student - Note: Removed teacher_id from the query
        $insertStmt = $conn->prepare("
            INSERT INTO tbl_student (
                tbl_student_id, 
                student_name, 
                course, 
                section, 
                generated_code,
                teacher_id
            ) VALUES (
                :student_id,
                :student_name,
                :course,
                :section,
                :generated_code,
                1  /* Setting a default teacher_id value of 1 */
            )
        ");

        // Bind parameters
        $insertStmt->bindParam(":student_id", $studentId, PDO::PARAM_INT);
        $insertStmt->bindParam(":student_name", $studentName, PDO::PARAM_STR);
        $insertStmt->bindParam(":course", $course, PDO::PARAM_STR);
        $insertStmt->bindParam(":section", $section, PDO::PARAM_STR);
        $insertStmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

        // Execute the insert
        $insertStmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect on success
        echo "<script>
                alert('Student added successfully!');
                window.location.href = '../attendance_admin.php';
              </script>";
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = '../html/attendance_admin.php';
              </script>";
        exit();
    }
} else {
    // If not POST request, redirect to main page
    header("Location: ./html/attendance_admin.php");
    exit();
}
?>

<?php
// Second Script - Attendance Recording
include('../conn/conn.php');
session_start();
date_default_timezone_set('Asia/Manila');

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
                $_SESSION['error'] = "Attendance already recorded for today.";
            } else {
                // Record new attendance
                $timeIn = date("Y-m-d H:i:s");
                $insertQuery = "INSERT INTO tbl_attendance (tbl_student_id, time_in) 
                               VALUES (:student_id, :time_in)";

                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
                $insertStmt->bindParam(':time_in', $timeIn, PDO::PARAM_STR);

                if ($insertStmt->execute()) {
                    $_SESSION['success'] = "Attendance recorded successfully.";
                } else {
                    $_SESSION['error'] = "Failed to record attendance.";
                }
            }
        } else {
            $_SESSION['error'] = "Invalid QR Code. No student found.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    // Preserve all query parameters from the current request
    $queryParams = $_GET;
    $queryString = http_build_query($queryParams);
    $redirectUrl = '../attendance_admin.php';
    if (!empty($queryString)) {
        $redirectUrl .= '?' . $queryString;
    }

    header("Location: " . $redirectUrl);
    exit();
} else {
    $_SESSION['error'] = "Please scan a valid QR code.";
    // Preserve query parameters even in error case
    $queryParams = $_GET;
    $queryString = http_build_query($queryParams);
    $redirectUrl = '../attendance_admin.php';
    if (!empty($queryString)) {
        $redirectUrl .= '?' . $queryString;
    }
    header("Location: " . $redirectUrl);
    exit();
}
?>