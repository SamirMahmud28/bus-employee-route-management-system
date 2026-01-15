<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $route_id = $_GET['id'];
    
    $sql = "DELETE FROM route WHERE route_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $route_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}

header("Location: dashboard.php");
exit();
?>
