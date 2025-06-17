<?php
session_start();
require __DIR__ . '/db.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle POST and file upload
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['service_error'] = "Invalid request.";
    header("Location: ../admin_manage_services.php");
    exit;
}

// Collect form data
$name = trim($_POST['service_name'] ?? '');
$description = trim($_POST['service_description'] ?? '');
$image = $_FILES['service_image'] ?? null;

// Validate input
if (empty($name) || empty($description) || !$image || $image['error'] !== 0) {
    $_SESSION['service_error'] = "All fields including image are required.";
    header("Location: ../admin_manage_services.php");
    exit;
}

// Validate image file type
$allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
$fileMime = mime_content_type($image['tmp_name']);

if (!array_key_exists($fileMime, $allowedTypes)) {
    $_SESSION['service_error'] = "Only JPG and PNG images are allowed.";
    header("Location: ../admin_manage_services.php");
    exit;
}

// Create safe filename
$extension = $allowedTypes[$fileMime];
$filename = 'service_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
$destination = __DIR__ . '/../img/' . $filename;

// Move file
if (!move_uploaded_file($image['tmp_name'], $destination)) {
    $_SESSION['service_error'] = "Failed to upload image.";
    header("Location: ../admin_manage_services.php");
    exit;
}

// Save to database
try {
    $stmt = $pdo->prepare("INSERT INTO services (service_name, service_description, service_image) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $filename]);

    $_SESSION['service_success'] = "Service added successfully.";
    header("Location: ../admin_manage_services.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['service_error'] = "Database error. Could not add service.";
    header("Location: ../admin_manage_services.php");
    exit;
}

