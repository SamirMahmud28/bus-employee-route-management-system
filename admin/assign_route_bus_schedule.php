<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';
require_once '../includes/header.php';

if (isset($_POST['assign_route_schedule'])) {
    $route_id = $_POST['route_id'];
    $bus_id = $_POST['bus_id'];
    $schedule_id = $_POST['schedule_id'];

    // Insert the new assignment into the Bus_Route_Schedule table
    $sql_assign = "INSERT INTO bus_route_schedule (route_id, bus_id, schedule_id) 
                   VALUES ('$route_id', '$bus_id', '$schedule_id')";
    if ($conn->query($sql_assign) === TRUE) {
        echo "<script>alert('Bus assigned to route and schedule successfully!');</script>";
    } else {
        echo "<script>alert('Error assigning bus to route and schedule: " . $conn->error . "');</script>";
    }
}

// Fetch routes, buses, and schedules
$sql_routes = "SELECT * FROM route";
$result_routes = $conn->query($sql_routes);

$sql_buses = "SELECT * FROM bus";
$result_buses = $conn->query($sql_buses);

$sql_schedules = "SELECT * FROM schedule";
$result_schedules = $conn->query($sql_schedules);

// Fetch current assignments, ordered by route name
$sql_assignments = "SELECT r.route_name, r.distance, b.bus_number, s.departure_time, s.arrival_time 
                    FROM bus_route_schedule brs
                    JOIN route r ON brs.route_id = r.route_id
                    JOIN bus b ON brs.bus_id = b.bus_id
                    JOIN schedule s ON brs.schedule_id = s.schedule_id
                    ORDER BY r.route_name";  // Order by route name
$result_assignments = $conn->query($sql_assignments);
?>

<div class="container">
    <!-- Admin Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Assign Bus, Route, and Schedule</h2>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Current Assignments Table -->
    <div class="row mb-4">
        <div class="col-12">
            <h3>Current Assignments</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Route Name</th>
                            <th>Assigned Bus</th>
                            <th>Distance (km)</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result_assignments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['route_name']; ?></td>
                            <td><?php echo $row['bus_number']; ?></td>
                            <td><?php echo $row['distance']; ?></td>
                            <td><?php echo $row['departure_time']; ?></td>
                            <td><?php echo $row['arrival_time']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assign New Route, Bus, and Schedule Form -->
    <div class="row mb-4">
        <div class="col-12">
            <h3>Assign New Bus to Route and Schedule</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="route_id" class="form-label">Select Route</label>
                    <select class="form-select" name="route_id" id="route_id" required>
                        <option value="" disabled selected>Select a route</option>
                        <?php while ($row = $result_routes->fetch_assoc()): ?>
                            <option value="<?php echo $row['route_id']; ?>"><?php echo $row['route_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bus_id" class="form-label">Select Bus</label>
                    <select class="form-select" name="bus_id" id="bus_id" required>
                        <option value="" disabled selected>Select a bus</option>
                        <?php while ($row = $result_buses->fetch_assoc()): ?>
                            <option value="<?php echo $row['bus_id']; ?>"><?php echo $row['bus_number']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="schedule_id" class="form-label">Select Schedule</label>
                    <select class="form-select" name="schedule_id" id="schedule_id" required>
                        <option value="" disabled selected>Select a schedule</option>
                        <?php while ($row = $result_schedules->fetch_assoc()): ?>
                            <option value="<?php echo $row['schedule_id']; ?>">
                                <?php echo $row['departure_time'] . ' - ' . $row['arrival_time']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="assign_route_schedule" class="btn btn-primary">Assign Bus to Route</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>