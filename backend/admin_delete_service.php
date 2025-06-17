<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['service_error'] = "Missing service ID.";
    header("Location: ../admin_manage_services.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['service_success'] = "Service deleted.";
    header("Location: ../admin_manage_services.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['service_error'] = "Could not delete service.";
    header("Location: ../admin_manage_services.php");
    exit;
}
