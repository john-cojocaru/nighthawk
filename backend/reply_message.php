<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);

if (!empty($subject) && !empty($message)) {
    $stmt = $pdo->prepare("
        INSERT INTO messages (user_id, sender_role, subject, message, status)
        VALUES (?, 'user', ?, ?, 'unread')
    ");
    $stmt->execute([$user_id, $subject, $message]);

    header("Location: ../messages.php");
    exit;
} else {
    $_SESSION['msg_error'] = "Please fill in all fields.";
    header("Location: ../messages.php");
    exit;
}
