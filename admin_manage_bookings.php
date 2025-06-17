<?php
session_start();
require_once 'backend/db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch bookings with joined user and service info
$sql = "
    SELECT 
        b.id, 
        b.booking_date, 
        b.notes, 
        b.status,
        u.full_name, 
        u.email,
        s.service_name, 
        s.service_image
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN services s ON b.service_id = s.id
    ORDER BY b.created_at DESC
";
$bookings = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Nighthawk Smart Solutions" class="logo" />
    </a>
    <h2 class="section-heading">Manage Bookings</h2>

    <div class="booking-table">
        <table>
            <thead>
            <tr>
                <th>Select</th>
                <th>Client</th>
                <th>Email</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Notes</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><input type="radio" name="selected_booking" value="<?= $booking['id'] ?>"></td>
                    <td><?= htmlspecialchars($booking['full_name']) ?></td>
                    <td><?= htmlspecialchars($booking['email']) ?></td>
                    <td>
                        <img src="img/<?= htmlspecialchars($booking['service_image']) ?>" alt="" width="60">
                        <br><?= htmlspecialchars($booking['service_name']) ?>
                    </td>
                    <td><?= date('Y-m-d', strtotime($booking['booking_date'])) ?></td>
                    <td><?= date('H:i', strtotime($booking['booking_date'])) ?></td>
                    <td><?= htmlspecialchars($booking['notes']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form id="bookingActions" method="POST" style="text-align: center; margin-top: 20px;">
        <input type="hidden" name="booking_id" id="booking_id">
        <button type="submit" formaction="admin_edit_booking.php" class="main-btn">✎ Edit</button>
        <button type="submit" formaction="backend/update_booking_status.php?status=confirmed" class="main-btn">✓ Complete</button>
        <button type="submit" formaction="backend/delete_booking.php" class="main-btn red">🗑 Delete</button>
    </form>

    <div class="bottom-nav" style="text-align: center; margin-top: 20px;">
        <a href="admin_dashboard.php" class="main-btn">← Back to Dashboard</a>
    </div>
</div>

<script>
    // Track selected booking
    document.querySelectorAll('input[name="selected_booking"]').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('booking_id').value = input.value;
        });
    });
</script>

</body>
</html>
