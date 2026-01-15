<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_id = $_POST['route_id'];
    $route_name = $_POST['route_name'];
    $distance = $_POST['distance'];

    $sql = "INSERT INTO route (route_id, route_name, distance) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $route_id, $route_name, $distance);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Add New Route</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Route ID</label>
                    <input type="text" name="route_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Route Name</label>
                    <input type="text" name="route_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Distance (km)</label>
                    <input type="number" name="distance" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Route</button>
            </form>
        </div>
    </div>
</div>