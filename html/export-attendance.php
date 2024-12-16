<?php
include './conn/conn.php';

$course = isset($_GET['course']) ? $_GET['course'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$startDate = '2024-11-10'; // Set start date to Nov 10, 2024
$endDate = '2024-12-15';   // Set end date to Dec 15, 2024
$currentDate = date('Y-m-d'); // Today's date

$whereClause = "1=1";
$params = [];

if (!empty($course)) {
    $whereClause .= " AND course = :course";
    $params[':course'] = $course;
}

if (!empty($section)) {
    $whereClause .= " AND section = :section";
    $params[':section'] = $section;
}

// First, get all holidays within the date range
$holidayQuery = $conn->prepare("SELECT holiday_date, holiday_name FROM tbl_holidays 
                               WHERE holiday_date BETWEEN :start_date AND :end_date");
$holidayQuery->bindValue(':start_date', $startDate);
$holidayQuery->bindValue(':end_date', $endDate);
$holidayQuery->execute();
$holidays = [];
while ($holiday = $holidayQuery->fetch(PDO::FETCH_ASSOC)) {
    $holidays[date("M j", strtotime($holiday['holiday_date']))] = $holiday['holiday_name'];
}

// Get all students based on the selected course and section
$studentsQuery = $conn->prepare("SELECT student_name FROM tbl_student WHERE $whereClause");
foreach ($params as $key => $value) {
    $studentsQuery->bindValue($key, $value);
}
$studentsQuery->execute();
$students = $studentsQuery->fetchAll(PDO::FETCH_ASSOC);

// Get attendance data
$attendanceQuery = "SELECT tbl_student.student_name, DATE(time_in) AS attendance_date 
                    FROM tbl_attendance 
                    LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id 
                    WHERE $whereClause 
                    AND DATE(time_in) BETWEEN :start_date AND :end_date 
                    AND time_in IS NOT NULL 
                    ORDER BY attendance_date, tbl_student.student_name";

$stmt = $conn->prepare($attendanceQuery);
$stmt->bindValue(':start_date', $startDate);
$stmt->bindValue(':end_date', $endDate);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Attendance_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');

// Column headers (dates in row)
$dates = [];
$dateLabels = [];
$current = strtotime($startDate);
$end = strtotime($endDate);
while ($current <= $end) {
    $dateStr = date("M j", $current);
    $dayOfWeek = date("l", $current);

    $dates[] = $dateStr;
    
    // Label weekends and holidays
    if (isset($holidays[$dateStr])) {
        $dateLabels[] = $dateStr . "\n(HOLIDAY: " . $holidays[$dateStr] . ")";
    } elseif ($dayOfWeek == "Saturday" || $dayOfWeek == "Sunday") {
        $dateLabels[] = $dateStr . "\n(W)";
    } else {
        $dateLabels[] = $dateStr;
    }
    $current = strtotime("+1 day", $current);
}
fputcsv($output, array_merge(['Student Name'], $dateLabels));

// Organize attendance by student and date
$attendanceSummary = [];
foreach ($attendanceRecords as $record) {
    $date = date("M j", strtotime($record['attendance_date']));
    $attendanceSummary[$record['student_name']][$date] = 'P'; // Mark as present with 'P'
}

// Populate rows with appropriate status for each date
foreach ($students as $student) {
    $studentName = $student['student_name'];
    $row = [$studentName];

    foreach ($dates as $date) {
        $dateInRange = date("Y-m-d", strtotime($date)) <= $currentDate;
        $dayOfWeek = date("l", strtotime($date));

        if (isset($holidays[$date])) {
            // Mark holidays with 'H'
            $row[] = 'H';
        } elseif ($dayOfWeek == "Saturday" || $dayOfWeek == "Sunday") {
            // Mark weekends with 'W'
            $row[] = 'W';
        } elseif ($dateInRange) {
            // If date is in the past, show 'P' for present, 'A' for absent
            $row[] = isset($attendanceSummary[$studentName][$date]) ? 'P' : 'A';
        } else {
            // Leave future dates blank
            $row[] = '';
        }
    }

    fputcsv($output, $row);
}

fclose($output);
exit;
?>
