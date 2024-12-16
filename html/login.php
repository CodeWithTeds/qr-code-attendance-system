<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        if ($user['role'] == 'attendance') {
            $_SESSION['user_id'] = $user['id'];
            header("Location: attendance.php");
        } else {
            $_SESSION['user_id'] = $user['id'];
            header("Location: attendance.php");
        }
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
