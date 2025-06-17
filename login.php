<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Nighthawk</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <h1>
    <a href="index.php" class="site-title">Nighthawk Smart Solutions</a>
  </h1>
  <h2>User Login</h2>

  <?php if (isset($_SESSION['login_error'])): ?>
    <div class="error-message">
      <?php echo htmlspecialchars($_SESSION['login_error']); ?>
    </div>
    <?php unset($_SESSION['login_error']); ?>
  <?php endif; ?>

  <form action="backend/user_login.php" method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button class="main-btn" type="submit">Login</button>
  </form>

  <p>
    Don't have an account? <a href="register.html" class="main-btn">Register here</a>
  </p>

</body>
</html>