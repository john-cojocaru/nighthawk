<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Nighthawk</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <h1>
    <a href="index.php" class="site-title">Nighthawk Smart Solutions</a>
  </h1>
  <h2>User Registration</h2>

  <?php if (isset($_SESSION['register_error'])): ?>
    <div class="error-message">
      <?php echo htmlspecialchars($_SESSION['register_error']); ?>
    </div>
    <?php unset($_SESSION['register_error']); ?>
  <?php endif; ?>

  <form action="backend/user_register.php" method="POST">
    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" required>

    <button class="main-btn" type="submit">Register</button>
  </form>

  <p>
    Already have an account? <a href="login.php" class="main-btn">Login here</a>
  </p>

</body>
</html>