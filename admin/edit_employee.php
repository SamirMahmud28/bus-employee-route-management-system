<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$employee_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $salary = $_POST['salary'];

    $sql = "UPDATE employee SET name = ?, salary = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $name, $salary, $employee_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}

$sql = "SELECT * FROM employee WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Edit Employee</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $employee['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="<?php echo $employee['role']; ?>" disabled>
                    <input type="hidden" name="role" value="<?php echo $employee['role']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Salary</label>
                    <input type="number" name="salary" class="form-control" value="<?php echo $employee['salary']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Employee</button>
            </form>
        </div>
    </div>
</div>
