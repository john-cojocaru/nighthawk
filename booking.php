<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$services = $pdo->query("SELECT id, service_name FROM services ORDER BY service_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Prefill for rebooking
$prefill = ['service_id' => '', 'booking_date' => '', 'booking_time' => '', 'notes' => ''];

if (isset($_GET['rebook'])) {
    $rebook_id = (int)$_GET['rebook'];
    $stmt = $pdo->prepare("SELECT service_id, booking_date, notes FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$rebook_id, $user_id]);
    $previous = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($previous) {
        $dt = new DateTime($previous['booking_date']);
        $prefill['service_id'] = $previous['service_id'];
        $prefill['booking_date'] = $dt->format('Y-m-d');
        $prefill['booking_time'] = $dt->format('H:i');
        $prefill['notes'] = $previous['notes'];
    }
}

// Get user bookings with images
$stmt = $pdo->prepare("
    SELECT b.*, s.service_name, s.service_image 
    FROM bookings b 
    JOIN services s ON b.service_id = s.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Bookings</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .hidden { display: none; }
    .section-title {
      text-align: center;
      margin-top: 40px;
      font-size: 26px;
      color: white;
    }
    .bottom-nav {
      display: flex;
      justify-content: center;
      margin: 50px 0 20px;
    }
    .main-btn.delete {
      background-color: #e74c3c;
    }
    .main-btn.delete:hover {
      background-color: #c0392b;
    }
    .service-icon {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
      margin-right: 12px;
      vertical-align: middle;
    }
    .admin-table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<a href="index.php">
  <img src="img/logo.png" alt="Nighthawk Logo" class="logo">
</a>

<h2>Welcome to Nighthawk Smart Solutions</h2>

<!-- Add Booking -->
<div class="button-bar">
  <a href="#bookingForm" onclick="document.getElementById('bookingForm').classList.remove('hidden');" class="main-btn">
    ➕ Add New Booking
  </a>
</div>

<h2 class="section-title">Your Bookings</h2>

<?php if (!empty($bookings)): ?>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($bookings as $b): ?>
        <?php
          $dt = new DateTime($b['booking_date']);
          $dateOnly = $dt->format('Y-m-d');
          $timeOnly = $dt->format('H:i');
        ?>
        <tr>
          <td>
            <a href="img/<?php echo htmlspecialchars($b['service_image']); ?>" target="_blank">
              <img src="img/<?php echo htmlspecialchars($b['service_image']); ?>" alt="service" class="service-icon">
            </a>
            <?php echo htmlspecialchars($b['service_name']); ?>
          </td>
          <td><?php echo $dateOnly; ?></td>
          <td><?php echo $timeOnly; ?></td>
          <td><?php echo nl2br(htmlspecialchars($b['notes'])); ?></td>
          <td><?php echo htmlspecialchars($b['status']); ?></td>
          <td>
            <a href="?rebook=<?php echo $b['id']; ?>" class="main-btn">🔁 Rebook</a>
            <?php if ($b['status'] === 'pending'): ?>
              <a href="backend/cancel_booking.php?id=<?php echo $b['id']; ?>"
                 class="main-btn delete"
                 onclick="return confirm('Cancel this booking?');">🗑 Cancel</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p class="section-title">You have no bookings yet.</p>
<?php endif; ?>

<!-- Booking Form -->
<div id="bookingForm" class="<?php echo isset($_GET['rebook']) ? '' : 'hidden'; ?>" style="margin-top: 40px;">
  <h3 style="text-align: center;"><?php echo isset($_GET['rebook']) ? 'Rebook a Service' : 'Book a Service'; ?></h3>

  <form action="backend/booking_handler.php" method="POST" style="max-width: 600px; margin: auto;">
    <label>Service</label>
    <select name="service_id" required>
      <option value="">-- Select Service --</option>
      <?php foreach ($services as $service): ?>
        <option value="<?php echo $service['id']; ?>"
          <?php echo ($service['id'] == $prefill['service_id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($service['service_name']); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Date</label>
    <input type="date" name="booking_date" value="<?php echo $prefill['booking_date']; ?>" required>

    <label>Time</label>
    <input type="time" name="booking_time" value="<?php echo $prefill['booking_time']; ?>" required>

    <label>Notes</label>
    <textarea name="notes" rows="4"><?php echo htmlspecialchars($prefill['notes']); ?></textarea>

    <button type="submit" class="main-btn">Submit Booking</button>
  </form>
</div>

<!-- Back to Home -->
<div class="bottom-nav">
  <a href="index.php" class="main-btn">← Back to Home</a>
</div>

<footer>
  <p style="text-align: center;">© 2025 Nighthawk Smart Solutions</p>
</footer>

</body>
</html>
