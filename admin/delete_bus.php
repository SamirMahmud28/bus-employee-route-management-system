<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';

// Get bus_id from URL
if (isset($_GET['id'])) {
    $bus_id = $_GET['id'];

    // First, delete the entries in bus_route_schedule that reference this bus
    $delete_bus_schedule_sql = "DELETE FROM bus_route_schedule WHERE bus_id = ?";
    if ($stmt = $conn->prepare($delete_bus_schedule_sql)) {
        $stmt->bind_param("i", $bus_id);
        $stmt->execute();
        $stmt->close();
    }

    // Now, delete the bus from the bus table
    $delete_bus_sql = "DELETE FROM bus WHERE bus_id = ?";
    if ($stmt = $conn->prepare($delete_bus_sql)) {
        $stmt->bind_param("i", $bus_id);
        if ($stmt->execute()) {
            header("Location: dashboard.php?message=Bus deleted successfully");
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>

