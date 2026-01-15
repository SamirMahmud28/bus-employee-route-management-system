<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';
require_once '../includes/header.php';

$sql_employees = "SELECT * FROM employee";
$result_employees = $conn->query($sql_employees);

$sql_stations = "SELECT * FROM station";
$result_stations = $conn->query($sql_stations);

// Handle form submission for assigning employee to a station
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_employee'])) {
    $employee_id = $_POST['employee_id'];
    $station_id = $_POST['station_id'];

    // Update the employee's station_id in the database
    $sql_assign = "UPDATE employee SET station_id = '$station_id' WHERE employee_id = '$employee_id'";
    if ($conn->query($sql_assign) === TRUE) {
        echo "<div class='alert alert-success'>Employee assigned to station successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Fetch assigned employees and their stations
$sql_assignments = "
    SELECT e.name AS employee_name, e.role AS employee_role, s.station_name, s.location 
    FROM employee e
    JOIN station s ON e.station_id = s.station_id
    WHERE e.station_id IS NOT NULL";
$result_assignments = $conn->query($sql_assignments);
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Assign Employee to Station</h2>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Section for Assigning Employee to Station -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info p-3" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h3 class="text-center mb-3">Assign Employee to Station</h3>
                <form method="POST" action="">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Select Employee</label>
                            <select class="form-select" id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                <?php while($row = $result_employees->fetch_assoc()): ?>
                                    <option value="<?php echo $row['employee_id']; ?>"><?php echo $row['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="station_id" class="form-label">Select Station</label>
                            <select class="form-select" id="station_id" name="station_id" required>
                                <option value="">Select Station</option>
                                <?php while($row = $result_stations->fetch_assoc()): ?>
                                    <option value="<?php echo $row['station_id']; ?>"><?php echo $row['station_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="assign_employee" class="btn btn-success btn-lg">Assign Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table to show assigned employees and their stations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info p-3" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h3 class="text-center mb-3">Assigned Employees to Stations</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Role</th>
                                <th>Station Name</th>
                                <th>Station Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result_assignments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['employee_name']; ?></td>
                                <td><?php echo $row['employee_role']; ?></td> <!-- New column for employee role -->
                                <td><?php echo $row['station_name']; ?></td>
                                <td><?php echo $row['location']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>