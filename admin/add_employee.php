<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $salary = $_POST['salary'];

    $conn->begin_transaction();

    try {
        // Insert into the `employee` table
        $sql = "INSERT INTO employee (employee_id, name, role, salary) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $employee_id, $name, $role, $salary);
        $stmt->execute();

        // Role-specific insertion
        if ($role === 'Supervisor') {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $sql = "INSERT INTO supervisor (employee_id, supervisor_id, start_date, end_date) VALUES (?, NULL, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $employee_id, $start_date, $end_date);
            $stmt->execute();
        } elseif ($role === 'Driver') {
            $license_number = $_POST['license_number'];
            $assigned_bus_id = $_POST['assigned_bus_id'] ?: NULL; // Allow NULL for unassigned buses
            $sql = "INSERT INTO driver (employee_id, license_number, assigned_bus_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $employee_id, $license_number, $assigned_bus_id);
            $stmt->execute();
        } elseif ($role === 'Admin') {
            $department = $_POST['department'];
            $office_location = $_POST['office_location'];
            $sql = "INSERT INTO admin (employee_id, department, office_location) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $employee_id, $department, $office_location);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Add New Employee</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Driver">Driver</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div id="supervisor-fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>
                <div id="driver-fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">License Number</label>
                        <input type="text" name="license_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assigned Bus ID</label>
                        <input type="number" name="assigned_bus_id" class="form-control">
                    </div>
                </div>
                <div id="admin-fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Office Location</label>
                        <input type="text" name="office_location" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Salary</label>
                    <input type="number" name="salary" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Employee</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('select[name="role"]').addEventListener('change', function() {
        const role = this.value;
        document.getElementById('supervisor-fields').style.display = role === 'Supervisor' ? 'block' : 'none';
        document.getElementById('driver-fields').style.display = role === 'Driver' ? 'block' : 'none';
        document.getElementById('admin-fields').style.display = role === 'Admin' ? 'block' : 'none';
    });
</script>