<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_manage_services.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    $_SESSION['service_error'] = "Service not found.";
    header("Location: admin_manage_services.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Edit Service</h2>

<form action="backend/admin_update_service.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">

    <label>Service Name</label>
    <input type="text" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>

    <label>Service Description</label>
    <textarea name="service_description" required><?php echo htmlspecialchars($service['service_description']); ?></textarea>

    <label>Current Image</label>
    <img src="img/<?php echo htmlspecialchars($service['service_image']); ?>" style="max-width:100%; height: auto; border-radius: 8px;">

    <label>Replace Image (optional)</label>
    <input type="file" name="service_image" accept="image/*">

    <input type="hidden" name="redirect" value="admin_manage_services.php">
    <button class="main-btn" type="submit">Save Changes</button>
</form>

<p><a href="admin_manage_services.php" class="main-btn">Back to Manage Services</a></p>

</body>
</html>
