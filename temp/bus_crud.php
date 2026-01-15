<?php
// bus_operations.php
require_once '../includes/config.php';

class BusOperations {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function addBus($bus_number, $capacity, $bus_type) {
        $sql = "INSERT INTO bus (bus_number, capacity, bus_type) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $bus_number, $capacity, $bus_type);
        
        return $stmt->execute();
    }
    
    public function updateBus($bus_id, $bus_number, $capacity, $bus_type) {
        $sql = "UPDATE bus SET bus_number=?, capacity=?, bus_type=? WHERE bus_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $bus_number, $capacity, $bus_type, $bus_id);
        
        return $stmt->execute();
    }
    
    public function deleteBus($bus_id) {
        $sql = "DELETE FROM bus WHERE bus_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bus_id);
        
        return $stmt->execute();
    }
    
    public function getBus($bus_id) {
        $sql = "SELECT * FROM bus WHERE bus_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $bus_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllBuses() {
        $sql = "SELECT * FROM bus";
        return $this->conn->query($sql);
    }
}


session_start();
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $busOps = new BusOperations($conn);
    
    $bus_number = htmlspecialchars($_POST['bus_number']);
    $capacity = (int)$_POST['capacity'];
    $bus_type = htmlspecialchars($_POST['bus_type']);
    
    if ($busOps->addBus($bus_number, $capacity, $bus_type)) {
        $_SESSION['success'] = "Bus added successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error adding bus.";
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Add New Bus</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form method="POST" action="add_bus.php">
                <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" name="bus_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" required min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Bus Type</label>
                    <select name="bus_type" class="form-control" required>
                        <option value="AC">AC</option>
                        <option value="Non-AC">Non-AC</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Bus</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/header.php';

$busOps = new BusOperations($conn);

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$bus_id = (int)$_GET['id'];
$bus = $busOps->getBus($bus_id);

if (!$bus) {
    $_SESSION['error'] = "Bus not found.";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_number = htmlspecialchars($_POST['bus_number']);
    $capacity = (int)$_POST['capacity'];
    $bus_type = htmlspecialchars($_POST['bus_type']);
    
    if ($busOps->updateBus($bus_id, $bus_number, $capacity, $bus_type)) {
        $_SESSION['success'] = "Bus updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating bus.";
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Edit Bus</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Bus Number</label>
                    <input type="text" name="bus_number" class="form-control" value="<?php echo htmlspecialchars($bus['bus_number']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="<?php echo (int)$bus['capacity']; ?>" required min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Bus Type</label>
                    <select name="bus_type" class="form-control" required>
                        <option value="AC" <?php echo $bus['bus_type'] == 'AC' ? 'selected' : ''; ?>>AC</option>
                        <option value="Non-AC" <?php echo $bus['bus_type'] == 'Non-AC' ? 'selected' : ''; ?>>Non-AC</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Bus</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
session_start();
require_once '../includes/config.php';

$busOps = new BusOperations($conn);

if (isset($_GET['id'])) {
    $bus_id = (int)$_GET['id'];
    
    if ($busOps->deleteBus($bus_id)) {
        $_SESSION['success'] = "Bus deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting bus.";
    }
}

header("Location: dashboard.php");
exit();
?>