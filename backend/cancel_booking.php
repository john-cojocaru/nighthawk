<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.html");
    exit;
}

$booking_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$booking_id) {
    header("Location: ../booking.php");
    exit;
}

// Delete only if it's the client's own and still pending
$stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ? AND status = 'pending'");
$stmt->execute([$booking_id, $user_id]);

header("Location: ../booking.php");
exit;
