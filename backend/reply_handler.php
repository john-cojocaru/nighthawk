<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message_id = $_POST['message_id'] ?? null;
$reply_message = trim($_POST['reply_message'] ?? '');

if ($message_id && $reply_message) {
    // Get original subject from the replied message
    $stmt = $pdo->prepare("SELECT subject FROM messages WHERE id = ? AND user_id = ?");
    $stmt->execute([$message_id, $user_id]);
    $original = $stmt->fetch();

    if ($original) {
        $subject = $original['subject'];

        // Insert the reply
        $replyStmt = $pdo->prepare("
            INSERT INTO messages (user_id, sender_role, subject, message, status)
            VALUES (?, 'user', ?, ?, 'unread')
        ");
        $replyStmt->execute([$user_id, $subject, $reply_message]);
    }
}

header("Location: ../messages.php");
exit;
