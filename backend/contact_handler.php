<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  if ($full_name && $email && $subject && $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (full_name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $subject, $message]);
    header("Location: ../contact.php?success=1");
    exit;
  } else {
    header("Location: ../contact.php?error=1");
    exit;
  }
}
