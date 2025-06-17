<?php
session_start();
require 'backend/db.php';

// Load featured services (latest 6)
$stmt = $pdo->query("SELECT service_name, service_description, service_image FROM services ORDER BY created_at DESC LIMIT 6");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle session state
$loggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null;
$name = $_SESSION['full_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nighthawk Smart Solutions</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <a href="index.php">
    <img src="img/logo.png" alt="Nighthawk Logo" class="logo">
  </a>
</header>

<h1>Welcome to Nighthawk Smart Solutions</h1>

<?php if ($loggedIn): ?>
  <div class="logged-in-label">
    Logged in as: <?php echo htmlspecialchars($name); ?> (<?php echo $role; ?>)
  </div>
<?php endif; ?>

<div class="button-bar">
  <a href="services.php" class="main-btn">Our Services</a>
  <a href="booking.php" class="main-btn">Book a Service</a>
  <a href="contact.php" class="main-btn">Contact Us</a>
  <?php if ($loggedIn): ?>
    <?php if ($role === 'admin'): ?>
      <a href="admin_dashboard.php" class="main-btn">Admin Panel</a>
    <?php endif; ?>
    <a href="logout.php" class="main-btn">Logout</a>
  <?php else: ?>
    <a href="login.php" class="main-btn">Login</a>
    <a href="register.php" class="main-btn">Register</a>
  <?php endif; ?>
</div>

<h2>Featured Services</h2>

<div class="services-grid">
  <?php foreach ($services as $service): ?>
    <div class="service-card">
      <img src="img/<?php echo htmlspecialchars($service['service_image']); ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
      <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
      <p><?php echo htmlspecialchars($service['service_description']); ?></p>
    </div>
  <?php endforeach; ?>
</div>

<footer>
  <p>&copy; 2025 Nighthawk Smart Solutions</p>
</footer>

</body>
</html>