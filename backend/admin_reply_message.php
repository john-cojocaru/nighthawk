<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $message = $_POST['message'] ?? null;

    if ($user_id && $subject && $message) {
        $stmt = $pdo->prepare("
            INSERT INTO messages (user_id, sender_role, subject, message, status, created_at)
            VALUES (?, 'admin', ?, ?, 'unread', NOW())
        ");
        $stmt->execute([$user_id, $subject, $message]);

        $_SESSION['reply_success'] = "Reply sent.";
        header("Location: ../admin_dashboard.php?section=messages");
        exit;
    } else {
        echo "Missing required fields.";
    }
} else {
    header("Location: ../admin_dashboard.php");
    exit;
}
?>
