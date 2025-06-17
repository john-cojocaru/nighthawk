<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$bookingId = $_POST['booking_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$bookingId || $action !== 'complete') {
    header("Location: ../admin_dashboard.php?section=bookings");
    exit;
}

$stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
$stmt->execute([$bookingId]);

$_SESSION['service_success'] = "Booking marked as completed.";
header("Location: ../admin_dashboard.php?section=bookings");
exit;
