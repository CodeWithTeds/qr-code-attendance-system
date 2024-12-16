<?php
include('../conn/conn.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $qrCode = $data['qr_code'] ?? '';

    if (!empty($qrCode)) {
        try {
            $stmt = $conn->prepare("SELECT tbl_student_id FROM tbl_student WHERE generated_code = :generated_code");
            $stmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch()) {
                echo json_encode(['valid' => true]);
            } else {
                echo json_encode(['valid' => false]);
            }
        } catch (PDOException $e) {
            echo json_encode(['valid' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['valid' => false, 'error' => 'Invalid request']);
    }
} else {
    echo json_encode(['valid' => false, 'error' => 'Invalid request method']);
}
