<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admin_dashboard.php?section=messages");
    exit;
}

// Mark as read
$pdo->prepare("UPDATE messages SET status = 'read' WHERE id = ?")->execute([$id]);

// Fetch message
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$msg = $stmt->fetch();

if (!$msg) {
    echo "<p style='text-align:center;'>Message not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Message</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .message-box {
      max-width: 800px;
      margin: 50px auto;
      background: #1a1a1a;
      color: white;
      padding: 30px;
      border-radius: 8px;
    }
    .message-box h3 {
      margin-bottom: 10px;
    }
    .message-box p {
      line-height: 1.6;
    }
    .btn-row {
      text-align: center;
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="message-box">
  <h3><?php echo htmlspecialchars($msg['subject']); ?></h3>
  <p><strong>From:</strong> <?php echo htmlspecialchars($msg['full_name']); ?> (<?php echo htmlspecialchars($msg['email']); ?>)</p>
  <p><strong>Sent:</strong> <?php echo $msg['created_at']; ?></p>
  <hr>
  <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>

  <div class="btn-row">
    <a href="admin_dashboard.php?section=messages" class="main-btn">← Back to Messages</a>
  </div>
</div>

</body>
</html>
