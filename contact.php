<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($subject) && !empty($message)) {
        $stmt = $pdo->prepare("
            INSERT INTO messages (user_id, sender_role, subject, message, status)
            VALUES (?, 'user', ?, ?, 'unread')
        ");
        $stmt->execute([$user_id, $subject, $message]);

        $_SESSION['msg_success'] = "Your message has been sent.";
        header("Location: messages.php");
        exit;
    } else {
        $error = "Please fill in both subject and message.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Contact Admin</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="index.php">
  <img src="img/logo.png" alt="Nighthawk Logo" class="logo">
</a>

<h2 style="text-align:center;">Contact Admin</h2>

<div class="form-container" style="max-width: 500px; margin: auto;">
  <?php if (!empty($error)): ?>
    <div style="color:red; text-align:center;"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="subject">Subject:</label>
    <input type="text" name="subject" id="subject" required>

    <label for="message">Message:</label>
    <textarea name="message" id="message" rows="6" required></textarea>

    <button type="submit" class="main-btn">Send Message</button>
  </form>

  <div style="text-align:center; margin-top:20px;">
    <a href="messages.php" class="main-btn">📨 View My Messages</a>
  </div>
</div>

</body>
</html>
