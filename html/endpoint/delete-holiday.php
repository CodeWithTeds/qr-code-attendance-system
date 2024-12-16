<?php
// endpoint/delete-holiday.php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $holiday_id = $_POST['holiday_id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM tbl_holidays WHERE holiday_id = :id");
        $stmt->bindParam(':id', $holiday_id);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>