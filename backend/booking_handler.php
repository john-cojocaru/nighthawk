<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $service_id = $_POST['service_id'] ?? null;
    $date = $_POST['booking_date'] ?? null;
    $time = $_POST['booking_time'] ?? null;
    $notes = trim($_POST['notes'] ?? '');

    // Combine date and time into full datetime string
    if ($date && $time) {
        $booking_date = "$date $time:00";
    } else {
        $_SESSION['register_error'] = "Please select both date and time.";
        header("Location: ../booking.php");
        exit;
    }

    if (!$user_id || !$service_id || !$booking_date) {
        $_SESSION['register_error'] = "Missing required booking data.";
        header("Location: ../booking.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO bookings (user_id, service_id, booking_date, notes, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$user_id, $service_id, $booking_date, $notes]);

        $_SESSION['register_success'] = true;
        header("Location: ../index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['register_error'] = "An error occurred while booking.";
        header("Location: ../booking.php");
        exit;
    }
}
