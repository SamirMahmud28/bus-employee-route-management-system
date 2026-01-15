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

$sql_buses = "SELECT * FROM bus";
$result_buses = $conn->query($sql_buses);

$sql_routes = "SELECT * FROM route";
$result_routes = $conn->query($sql_routes);
?>

<div class="container">
    <!-- Admin Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Welcome to the Admin Dashboard</h2>
                <a href="../admin/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <!-- Enhanced Section for Assigning Route, Bus, and Schedule -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info p-3" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h3 class="text-center mb-3">Manage Bus, Route, and Schedule</h3>
                <p class="text-center">Click below to assign buses to routes and schedules for effective management.</p>
                <div class="text-center">
                    <a href="assign_route_bus_schedule.php" class="btn btn-success btn-lg">Assign Route, Bus, and Schedule</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Section for Assigning Employee to Station -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info p-3" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h3 class="text-center mb-3">Assign Employee to Station</h3>
            <p class="text-center">Click below to assign an employee to a station for management purposes.</p>
            <div class="text-center">
                <a href="assign_employee_to_station.php" class="btn btn-success btn-lg">Assign Employee to Station</a>
            </div>
        </div>
    </div>
</div>
    

    <!-- Employee Management Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Employee Management</h2>
                <a href="add_employee.php" class="btn btn-primary">Add New Employee</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result_employees->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['employee_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td><?php echo $row['salary']; ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $row['employee_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $row['employee_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bus Management Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Bus Management</h2>
                <a href="add_bus.php" class="btn btn-primary">Add New Bus</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bus Number</th>
                            <th>Capacity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result_buses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['bus_id']; ?></td>
                            <td><?php echo $row['bus_number']; ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td>
                                <a href="edit_bus.php?id=<?php echo $row['bus_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_bus.php?id=<?php echo $row['bus_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Route Management Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Route Management</h2>
                <a href="add_route.php" class="btn btn-primary">Add New Route</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Route Name</th>
                            <th>Distance (km)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result_routes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['route_id']; ?></td>
                            <td><?php echo $row['route_name']; ?></td>
                            <td><?php echo $row['distance']; ?></td>
                            <td>
                                <a href="edit_route.php?id=<?php echo $row['route_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete_route.php?id=<?php echo $row['route_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>