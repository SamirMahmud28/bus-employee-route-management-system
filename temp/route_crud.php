<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_name = $_POST['route_name'];
    $distance = $_POST['distance'];
    
    $sql = "INSERT INTO route (route_name, distance) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $route_name, $distance);
    
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
                    <label class="form-label">Route Name</label>
                    <input type="text" name="route_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Distance (km)</label>
                    <input type="number" step="0.1" name="distance" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Route</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$route_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_name = $_POST['route_name'];
    $distance = $_POST['distance'];
    
    $sql = "UPDATE route SET route_name=?, distance=? WHERE route_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $route_name, $distance, $route_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}

$sql = "SELECT * FROM route WHERE route_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();
$route = $result->fetch_assoc();
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Edit Route</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Route Name</label>
                    <input type="text" name="route_name" class="form-control" value="<?php echo $route['route_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Distance (km)</label>
                    <input type="number" step="0.1" name="distance" class="form-control" value="<?php echo $route['distance']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Route</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $route_id = $_GET['id'];
    
    $sql = "DELETE FROM route WHERE route_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $route_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}

header("Location: dashboard.php");
exit();
?>