<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_dashboard.php");
    exit;
}

$id = $_POST['id'] ?? null;
$name = trim($_POST['service_name']);
$description = trim($_POST['service_description']);
$image = $_FILES['service_image'] ?? null;

if (!$id || !$name || !$description) {
    $_SESSION['service_error'] = "All fields are required.";
    header("Location: ../admin_edit_service.php?id=$id");
    exit;
}

try {
    if ($image && $image['size'] > 0 && $image['error'] === 0) {
        $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $fileMime = mime_content_type($image['tmp_name']);

        if (!array_key_exists($fileMime, $allowedTypes)) {
            $_SESSION['service_error'] = "Invalid image type.";
            header("Location: ../admin_edit_service.php?id=$id");
            exit;
        }

        $ext = $allowedTypes[$fileMime];
        $filename = 'service_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $dest = __DIR__ . '/../img/' . $filename;

        if (!move_uploaded_file($image['tmp_name'], $dest)) {
            $_SESSION['service_error'] = "Failed to upload image.";
            header("Location: ../admin_edit_service.php?id=$id");
            exit;
        }

        $stmt = $pdo->prepare("UPDATE services SET service_name = ?, service_description = ?, service_image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $filename, $id]);

    } else {
        $stmt = $pdo->prepare("UPDATE services SET service_name = ?, service_description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);
    }

    $_SESSION['service_success'] = "Service updated.";
    header("Location: ../admin_manage_services.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['service_error'] = "Update failed.";
    header("Location: ../admin_edit_service.php?id=$id");
    exit;
}


    $redirect = $_POST['redirect'] ?? 'admin_manage_services.php';
    header("Location: $redirect");
    exit;
