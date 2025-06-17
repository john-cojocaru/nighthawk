<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$section = $_GET['section'] ?? null;
$filter = $_GET['filter'] ?? 'unread';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<a href="index.php"><img src="img/logo.png" class="logo" alt="Nighthawk Logo"></a>
<h1>Admin Dashboard</h1>

<div class="button-bar">
  <a href="admin_manage_services.php" class="main-btn">Manage Services</a>
  <a href="admin_manage_bookings.php" class="main-btn">Manage Bookings</a>
  <a href="admin_dashboard.php?section=messages" class="main-btn">Manage Messages</a>
  <a href="logout.php" class="main-btn">Logout</a>
</div>

<?php if ($section === 'services'): ?>
  <?php
  $stmt = $pdo->query("SELECT id, service_name, service_description, service_image FROM services ORDER BY created_at DESC");
  $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <h3>Manage Services</h3>
  <div class="services-grid">
    <?php foreach ($services as $service): ?>
      <div class="service-card">
        <img src="img/<?php echo htmlspecialchars($service['service_image']); ?>" alt="">
        <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
        <p><?php echo htmlspecialchars($service['service_description']); ?></p>
        <div class="action-buttons">
          <a href="admin_edit_service.php?id=<?php echo $service['id']; ?>" class="main-btn">Edit</a>
          <a href="backend/admin_delete_service.php?id=<?php echo $service['id']; ?>" class="main-btn" onclick="return confirm('Delete this service?');">Delete</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <h3>Add New Service</h3>
  <form action="backend/admin_add_service.php" method="POST" enctype="multipart/form-data">
    <label>Service Name</label>
    <input type="text" name="service_name" required>
    <label>Service Description</label>
    <textarea name="service_description" required></textarea>
    <label>Service Image</label>
    <input type="file" name="service_image" accept="image/*" required>
    <button type="submit" class="main-btn">Add Service</button>
  </form>

<?php elseif ($section === 'bookings'): ?>
  <?php
  $stmt = $pdo->query("
    SELECT b.id, u.full_name, u.email, s.service_name, b.booking_date, b.notes, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN services s ON b.service_id = s.id
    ORDER BY b.booking_date DESC
  ");
  $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <h3>Manage Bookings</h3>
  <?php if (count($bookings) === 0): ?>
    <p>No bookings found.</p>
  <?php else: ?>
    <form method="POST">
      <table class="admin-table">
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
            <?php
              $dt = new DateTime($booking['booking_date']);
              $date = $dt->format('Y-m-d');
              $time = $dt->format('H:i');
            ?>
            <tr>
              <td><input type="radio" name="selected_booking" value="<?php echo $booking['id']; ?>"></td>
              <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
              <td><?php echo htmlspecialchars($booking['email']); ?></td>
              <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
              <td><?php echo $date; ?></td>
              <td><?php echo $time; ?></td>
              <td><?php echo nl2br(htmlspecialchars($booking['notes'])); ?></td>
              <td><?php echo htmlspecialchars($booking['status']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="booking-action-buttons">
        <button type="submit" formaction="admin_edit_booking.php" class="main-btn">✏ Edit</button>
        <button type="submit" formaction="backend/update_booking_status.php" class="main-btn">✔ Complete</button>
        <button type="submit" formaction="backend/delete_booking.php" class="main-btn delete">🗑 Delete</button>
      </div>
    </form>
  <?php endif; ?>

<?php elseif ($section === 'messages'): ?>
  <h3>📬 Manage Messages</h3>

  <div class="button-bar">
    <a href="admin_dashboard.php?section=messages&filter=unread" class="main-btn">Unread</a>
    <a href="admin_dashboard.php?section=messages&filter=sent" class="main-btn">Sent</a>
    <a href="admin_dashboard.php?section=messages&filter=all" class="main-btn">All</a>
  </div>

  <?php
    if ($filter === 'unread') {
      $stmt = $pdo->prepare("SELECT * FROM messages WHERE receiver_role = 'admin' AND status = 'unread' ORDER BY created_at DESC");
      $stmt->execute();
    } elseif ($filter === 'sent') {
      $stmt = $pdo->prepare("SELECT * FROM messages WHERE sender_role = 'admin' ORDER BY created_at DESC");
      $stmt->execute();
    } else {
      $stmt = $pdo->prepare("SELECT * FROM messages WHERE receiver_role = 'admin' OR sender_role = 'admin' ORDER BY created_at DESC");
      $stmt->execute();
    }

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php if (count($messages) === 0): ?>
    <p style="color: #ccc; text-align: center;">📭 No messages found in this category.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Status</th>
          <th>User</th>
          <th>Subject</th>
          <th>Received</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
          <tr>
            <td><?php echo $msg['status'] === 'unread' ? '📪 Unread' : '✅ Read'; ?></td>
            <td><?php echo htmlspecialchars($msg['full_name']); ?></td>
            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
            <td><?php echo $msg['created_at']; ?></td>
            <td>
              <form action="admin_view_message.php" method="GET">
                <input type="hidden" name="thread_id" value="<?php echo $msg['thread_id']; ?>">
                <button type="submit" class="main-btn">📄 Open</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
<?php endif; ?>

<footer>
  <p>&copy; 2025 Nighthawk Smart Solutions</p>
</footer>

</body>
</html>
