<?php
session_start();
require __DIR__ . '/db.php';

// Prevent direct access via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit;
}

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if (empty($full_name) || empty($email) || empty($password)) {
    $_SESSION['register_error'] = "Please fill in all fields.";
    header("Location: ../register.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = "Invalid email format.";
    header("Location: ../register.php");
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['register_error'] = "Passwords do not match.";
    header("Location: ../register.php");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $_SESSION['register_error'] = "Email already registered.";
        header("Location: ../register.php");
        exit;
    }

    // Insert new user
    $insert = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, 'client')");
    $insert->execute([$full_name, $email, $hashed_password]);

    // Auto-login
    $user_id = $pdo->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['full_name'] = $full_name;
    $_SESSION['role'] = 'client';
    $_SESSION['register_success'] = true;

    header("Location: ../index.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['register_error'] = "An error occurred. Please try again.";
    header("Location: ../register.php");
    exit;
}
?>