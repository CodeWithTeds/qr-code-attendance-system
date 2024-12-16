<?php

include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $holiday_date = $_POST['holiday_date'];
    $holiday_name = $_POST['holiday_name'];
    $description = $_POST['description'];

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_holidays (holiday_date, holiday_name, description) VALUES (:date, :name, :description)");
        $stmt->bindParam(':date', $holiday_date);
        $stmt->bindParam(':name', $holiday_name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        header('Location: ../manage_attendance.php?message=Holiday added successfully');
    } catch (PDOException $e) {
        header('Location: ../manage_attendance.php?error=Failed to add holiday');
    }
}
