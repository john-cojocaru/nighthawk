<?php
session_start();
require 'backend/db.php';

// Fetch all services from DB
$stmt = $pdo->query("SELECT service_name, service_description, service_image FROM services ORDER BY created_at DESC");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Services | Nighthawk</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <a href="index.php">
    <img src="img/logo.png" alt="Nighthawk Logo" class="logo">
  </a>
</header>

<h1>Our Services</h1>

<div class="services-grid">
  <?php foreach ($services as $service): ?>
    <div class="service-card">
      <img src="img/<?php echo htmlspecialchars($service['service_image']); ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
      <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
      <p><?php echo htmlspecialchars($service['service_description']); ?></p>
    </div>
  <?php endforeach; ?>
</div>

<div style="text-align: center; margin-top: 30px;">
  <a href="index.php" class="main-btn">Back to Home</a>
</div>

<footer>
  <p>&copy; 2025 Nighthawk Smart Solutions</p>
</footer>

</body>
</html>
