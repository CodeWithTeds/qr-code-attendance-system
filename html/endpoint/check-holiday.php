<?php
include './conn/conn.php';
header('Content-Type: application/json');
$date = isset($_GET['date']) ? $_GET['date'] : null;

if ($date) {
    $stmt = $conn->prepare("SELECT * FROM tbl_holidays WHERE holiday_date = :date");
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $holiday = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($holiday) {
        echo json_encode([
            'isHoliday' => true,
            'holiday_name' => $holiday['holiday_name'],
            'description' => $holiday['description']
        ]);
    } else {
        echo json_encode(['isHoliday' => false]);
    }
} else {
    echo json_encode(['isHoliday' => false]);
}
?>