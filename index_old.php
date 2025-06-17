<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nighthawk Smart Solutions - Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <a href="index.php">
    <img src="img/logo.png" alt="Nighthawk Logo" class="logo">
  </a>

  <?php if (isset($_SESSION['user_id'])): ?>
    <?php if (isset($_SESSION['register_success'])): ?>
      <p>Registered successfully and logged in as: <?php echo htmlspecialchars($_SESSION['full_name']); ?> (<?php echo $_SESSION['role']; ?>)</p>
      <?php unset($_SESSION['register_success']); ?>
    <?php else: ?>
      <p>Logged in as: <?php echo htmlspecialchars($_SESSION['full_name']); ?> (<?php echo $_SESSION['role']; ?>)</p>
    <?php endif; ?>
  <?php endif; ?>

  <div class="button-bar">
    <?php if (isset($_SESSION['user_id'])): ?>
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php" class="main-btn">Admin Dashboard</a>
      <?php endif; ?>
      <a href="services.php" class="main-btn">Services</a>
      <a href="booking.php" class="main-btn">Booking</a>
      <a href="contact.php" class="main-btn">Contact</a>
      <a href="backend/logout.php" class="main-btn">Logout</a>
    <?php else: ?>
      <a href="login.php" class="main-btn">Login</a>
      <a href="register.php" class="main-btn">Register</a>

      <a href="booking.php" class="main-btn">Booking</a>
      <a href="contact.php" class="main-btn">Contact</a>
    <?php endif; ?>
  </div>

  <h2>Our Services</h2>

  <div class="services-grid">

    <div class="service-card">
      <img src="img/service1.jpg" alt="Structured Cabling Installation">
      <h3>Structured Cabling Installation</h3>
      <p>Professional structured cabling for commercial buildings and data centers to ensure high-speed and reliable network infrastructure.</p>
    </div>

    <div class="service-card">
      <img src="img/service2.jpg" alt="Fiber Optic Installation">
      <h3>Fiber Optic Installation</h3>
      <p>Full-service fiber optic installation with high-speed internet connectivity for businesses and smart buildings.</p>
    </div>

    <div class="service-card">
      <img src="img/service3.jpg" alt="Smart Home Automation">
      <h3>Smart Home Automation</h3>
      <p>Custom smart home systems including lighting, HVAC, security, and voice control for modern living.</p>
    </div>

    <div class="service-card">
      <img src="img/service4.jpg" alt="Wireless Network Setup">
      <h3>Wireless Network Setup</h3>
      <p>Design and deployment of secure, high-performance wireless networks across large office or industrial spaces.</p>
    </div>

    <div class="service-card">
      <img src="img/service5.jpg" alt="CCTV & Security Systems">
      <h3>CCTV & Security Systems</h3>
      <p>Installation and integration of advanced CCTV, alarm, and remote monitoring systems for enhanced building security.</p>
    </div>

    <div class="service-card">
      <img src="img/service6.jpg" alt="Server Room Setup & Maintenance">
      <h3>Server Room Setup & Maintenance</h3>
      <p>Professional server room design, cabling, equipment installation, cooling, and ongoing maintenance services.</p>
    </div>

  </div>

  <p>
    <a href="services.php" class="main-btn">View All Services</a>
  </p>

  <footer>
    <p>&copy; 2025 Nighthawk Smart Solutions</p>
  </footer>

</body>
</html>