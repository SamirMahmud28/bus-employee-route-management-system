<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$bus_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_number = $_POST['bus_number'];
    $capacity = $_POST['capacity'];

    $sql = "UPDATE bus SET bus_number = ?, capacity = ? WHERE bus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $bus_number, $capacity, $bus_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}

$sql = "SELECT * FROM bus WHERE bus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Edit Bus</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" name="bus_number" class="form-control" value="<?php echo $bus['bus_number']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="<?php echo $bus['capacity']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Bus</button>
            </form>
        </div>
    </div>
</div>
