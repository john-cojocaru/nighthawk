<?php
session_start();
require_once 'backend/db.php';

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all services
$stmt = $pdo->query("SELECT id, service_name, service_description, service_image FROM services ORDER BY created_at DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services - Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php">
            <img src="img/logo.png" alt="Nighthawk Smart Solutions" class="logo" />
        </a>
        <h1 class="section-heading">Manage Services</h1>

    <div class="services-grid">
    <?php foreach ($services as $service): ?>
      <div class="service-card">
        <img src="img/<?php echo htmlspecialchars($service['service_image']); ?>" alt="">
        <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
        <p><?php echo htmlspecialchars($service['service_description']); ?></p>
        <div class="action-buttons">
          <a href="admin_edit_service.php?id=<?php echo $service['id']; ?>" class="main-btn">Edit</a>
          <input type="hidden" name="redirect" value="admin_manage_services.php">
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
    <input type="hidden" name="redirect" value="admin_manage_services.php">
    <button type="submit" class="main-btn">Add Service</button>
  </form>


<div class="bottom-nav">
  <a href="admin_dashboard.php" class="main-btn">← Back to Dashboard</a>
</div>

<footer>
  <p style="text-align: center;">© 2025 Nighthawk Smart Solutions</p>
</footer>

</body>
</html>
