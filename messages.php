<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$isAdmin = ($_SESSION['role'] === 'admin');
$filter = $_GET['filter'] ?? 'unread';
$threadId = $_GET['thread_id'] ?? null;

// Load filtered messages
$params = [];
$query = "SELECT * FROM messages WHERE ";

if ($isAdmin) {
    if ($filter === 'sent') {
        $query .= "sender_role = 'admin' ";
    } elseif ($filter === 'unread') {
        $query .= "status = 'unread' AND receiver_role = 'admin' ";
    } else {
        $query = "SELECT * FROM messages ORDER BY created_at DESC";
    }
} else {
    $query .= "user_id = :userId ";
    $params[':userId'] = $userId;

    if ($filter === 'sent') {
        $query .= "AND sender_role = 'user' ";
    } elseif ($filter === 'unread') {
        $query .= "AND sender_role = 'admin' AND status = 'unread' ";
    }
    $query .= "ORDER BY created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Load thread and mark as read
if ($threadId) {
 $mark = $pdo->prepare("UPDATE messages 
    SET status = 'read' 
    WHERE (thread_id = :threadId OR id = :msgId) 
    AND receiver_role = :receiver");

$mark->execute([
    ':threadId' => $threadId,
    ':msgId' => $threadId,
    ':receiver' => $isAdmin ? 'admin' : 'user'
]);

$threadStmt = $pdo->prepare("
    SELECT * FROM messages 
    WHERE thread_id = :threadId OR id = :msgId 
    ORDER BY created_at ASC
");
$threadStmt->execute([
    ':threadId' => $threadId,
    ':msgId' => $threadId
]);
    $threadMessages = $threadStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>📬 Messages</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="index.php"><img src="img/logo.png" class="logo" alt="Logo"></a>
<h2 class="section-title">📬 Your Messages</h2>

<div class="button-bar" style="margin-bottom: 30px;">
  <a href="?filter=unread" class="main-btn">Unread</a>
  <a href="?filter=sent" class="main-btn">Sent</a>
  <a href="?filter=all" class="main-btn">All</a>
</div>

<?php if ($threadId && !empty($threadMessages)): ?>
  <div class="message-thread-container">
    <?php foreach ($threadMessages as $msg): ?>
      <div class="message-thread <?php echo $msg['sender_role'] === 'user' ? 'user-msg' : 'admin-msg'; ?>">
        <strong><?php echo ucfirst($msg['sender_role']); ?></strong>
        <small><?php echo $msg['created_at']; ?></small>
        <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
      </div>
    <?php endforeach; ?>

    <form method="POST" action="backend/message_reply.php" style="margin-top:20px;">
      <input type="hidden" name="thread_id" value="<?php echo $threadId; ?>">
      <textarea name="message" required placeholder="Write your reply." rows="5"></textarea>
      <button type="submit" class="main-btn">📤 Send Reply</button>
    </form>
  </div>

<?php elseif (!empty($messages)): ?>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Status</th>
        <th>From</th>
        <th>Subject</th>
        <th>Received</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?php echo $msg['status'] === 'unread' ? '📩 Unread' : '✅ Read'; ?></td>
          <td><?php echo htmlspecialchars($msg['sender_role'] === 'admin' ? 'Admin' : $_SESSION['full_name']); ?></td>
          <td><?php echo htmlspecialchars($msg['subject']); ?></td>
          <td><?php echo $msg['created_at']; ?></td>
          <td>
            <a href="?thread_id=<?php echo $msg['thread_id'] ?? $msg['id']; ?>" class="main-btn">📖 Open</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p style="text-align:center;">📭 No messages found in this category.</p>
<?php endif; ?>

<div style="text-align: center; margin: 40px 0;">
  <a href="index.php" class="main-btn">⬅ Back to Home</a>
</div>

</body>
</html>
