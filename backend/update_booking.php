<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Get POST data
$booking_id = $_POST['booking_id'] ?? null;
$service_id = $_POST['service_id'] ?? null;
$date = $_POST['booking_date'] ?? null;
$time = $_POST['booking_time'] ?? null;
$notes = trim($_POST['notes'] ?? '');
$status = $_POST['status'] ?? 'pending';

// Validate input
if (!$booking_id || !$service_id || !$date || !$time) {
    $_SESSION['service_error'] = "Missing required booking details.";
    header("Location: ../admin_dashboard.php?section=bookings");
    exit;
}

// Combine date and time into full datetime
$booking_date = "$date $time:00";

// Update database
try {
    $stmt = $pdo->prepare("
        UPDATE bookings
        SET service_id = ?, booking_date = ?, notes = ?, status = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $service_id,
        $booking_date,
        $notes,
        $status,
        $booking_id
    ]);

    $_SESSION['service_success'] = "Booking updated successfully.";
    header("Location: ../admin_dashboard.php?section=bookings");
    exit;
} catch (PDOException $e) {
    $_SESSION['service_error'] = "Error updating booking.";
    header("Location: ../admin_dashboard.php?section=bookings");
    exit;
}
