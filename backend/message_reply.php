<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['message']) || !isset($_POST['thread_id'])) {
    header('Location: ../messages.php');
    exit;
}

$senderId = $_SESSION['user_id'];
$senderRole = $_SESSION['role'];
$threadId = $_POST['thread_id'];
$messageText = trim($_POST['message']);

if (empty($messageText)) {
    header('Location: ../messages.php?error=empty');
    exit;
}

// Get the original message to pull metadata
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ? OR thread_id = ? LIMIT 1");
$stmt->execute([$threadId, $threadId]);
$original = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$original) {
    header('Location: ../messages.php?error=thread');
    exit;
}

// Determine receiver role
$receiverRole = $original['sender_role'] === 'admin' ? 'user' : 'admin';

$replyStmt = $pdo->prepare("
    INSERT INTO messages (user_id, thread_id, subject, message, sender_role, receiver_role, status, created_at)
    VALUES (:user_id, :thread_id, :subject, :message, :sender_role, :receiver_role, 'unread', NOW())
");

$replyStmt->execute([
    ':user_id' => $original['user_id'],
    ':thread_id' => $original['thread_id'] ?: $original['id'],
    ':subject' => $original['subject'],
    ':message' => $messageText,
    ':sender_role' => $senderRole,
    ':receiver_role' => $receiverRole
]);

header("Location: ../messages.php?thread_id=" . ($original['thread_id'] ?: $original['id']));
exit;
