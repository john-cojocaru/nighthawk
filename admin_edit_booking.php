<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$bookingId = $_POST['booking_id'] ?? null;

if (!$bookingId) {
    header("Location: admin_dashboard.php?section=bookings");
    exit;
}

// Load booking data
$stmt = $pdo->prepare("
  SELECT b.id, b.service_id, b.booking_date, b.notes, b.status,
         u.full_name, u.email
  FROM bookings b
  JOIN users u ON b.user_id = u.id
  WHERE b.id = ?
");
$stmt->execute([$bookingId]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    $_SESSION['service_error'] = "Booking not found.";
    header("Location: admin_dashboard.php?section=bookings");
    exit;
}

// Extract date and time from datetime
$datetime = new DateTime($booking['booking_date']);
$dateValue = $datetime->format('Y-m-d');
$timeValue = $datetime->format('H:i');

// Load available services
$services = $pdo->query("SELECT id, service_name FROM services ORDER BY service_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="admin_dashboard.php?section=bookings" class="main-btn">← Back to Bookings</a>

<h2>Edit Booking</h2>

<form action="backend/update_booking.php" method="POST">
  <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">

  <label>Client Name</label>
  <input type="text" value="<?php echo htmlspecialchars($booking['full_name']); ?>" disabled>

  <label>Client Email</label>
  <input type="email" value="<?php echo htmlspecialchars($booking['email']); ?>" disabled>

  <label>Service</label>
  <select name="service_id" required>
    <?php foreach ($services as $service): ?>
      <option value="<?php echo $service['id']; ?>"
        <?php echo ($booking['service_id'] == $service['id']) ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars($service['service_name']); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Booking Date</label>
  <input type="date" name="booking_date" value="<?php echo $dateValue; ?>" required>

  <label>Booking Time</label>
  <input type="time" name="booking_time" value="<?php echo $timeValue; ?>" required>

  <label>Notes</label>
  <textarea name="notes" rows="4"><?php echo htmlspecialchars($booking['notes']); ?></textarea>

  <label>Status</label>
  <select name="status">
    <option value="pending" <?php echo ($booking['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
    <option value="completed" <?php echo ($booking['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
  </select>

  <button type="submit" class="main-btn">Save Changes</button>
</form>

</body>
</html>
