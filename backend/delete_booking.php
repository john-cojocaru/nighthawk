<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$bookingId = $_POST['booking_id'] ?? null;

if (!$bookingId) {
    header("Location: ../admin_dashboard.php?section=bookings");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
$stmt->execute([$bookingId]);

$_SESSION['service_success'] = "Booking deleted.";
header("Location: ../admin_dashboard.php?section=bookings");
exit;
