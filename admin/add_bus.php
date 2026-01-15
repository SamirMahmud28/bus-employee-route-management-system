<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_id = $_POST['bus_id'];
    $bus_number = $_POST['bus_number'];
    $capacity = $_POST['capacity'];

    $sql = "INSERT INTO bus (bus_id, bus_number, capacity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $bus_id, $bus_number, $capacity);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Add New Bus</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Bus ID</label>
                    <input type="text" name="bus_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" name="bus_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Bus</button>
            </form>
        </div>
    </div>
</div>