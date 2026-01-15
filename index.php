<?php
require_once 'includes/config.php';
$page_title = 'Home';
require_once 'includes/header.php';

// Fetch all routes for the dropdown menu
$sql_routes = "SELECT * FROM route";
$result_routes = $conn->query($sql_routes);

// Initialize route details
$route_details = [];

// Check if a route is selected
if (isset($_GET['route_id']) && $_GET['route_id'] !== '') {
    $route_id = $_GET['route_id'];

    // Query to get details for the selected route
    $sql_details = "
        SELECT 
            r.route_name, 
            r.distance, 
            b.bus_number, 
            b.capacity, 
            s.departure_time, 
            s.arrival_time
        FROM
            route r
        JOIN bus_route_schedule brs ON r.route_id = brs.route_id
        JOIN bus b ON brs.bus_id = b.bus_id
        JOIN schedule s ON brs.schedule_id = s.schedule_id
        WHERE r.route_id = ?";
    
    $stmt = $conn->prepare($sql_details);
    $stmt->bind_param("i", $route_id);
    $stmt->execute();
    $route_details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch data for employees and buses as before
$sql_employees = "SELECT * FROM employee";
$result_employees = $conn->query($sql_employees);

$sql_buses = "SELECT * FROM bus";
$result_buses = $conn->query($sql_buses);

// Fetch stations for dropdown selection
$sql_stations = "SELECT * FROM station";
$result_stations = $conn->query($sql_stations);

// Fetch assigned employees and their stations
$sql_assignments = "
    SELECT e.name AS employee_name, e.role AS employee_role, s.station_name, s.location 
    FROM employee e
    JOIN station s ON e.station_id = s.station_id
    WHERE e.station_id IS NOT NULL";
$result_assignments = $conn->query($sql_assignments);
?>

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title gradient-text">Bus and Employee Management System</h1>
        <p class="lead text-white-50">Scroll Below to See the Database Contents</p>
    </div>
</section>

<!-- Route Selection Section -->
<section class="content-section">
    <div class="container">
        <div class="glass-card route-selection-card">
            <h2>See Available Buses and Schedules</h2>
            <form method="GET">
                <label for="route" class="form-label">Select a Route</label>
                <select name="route_id" id="route" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select a Route --</option>
                    <?php while ($row = $result_routes->fetch_assoc()): ?>
                        <option value="<?php echo $row['route_id']; ?>" <?php if (isset($route_id) && $route_id == $row['route_id']) echo 'selected'; ?>>
                            <?php echo $row['route_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <?php if (!empty($route_details)): ?>
                <h2>Route Details</h2>
                <p><strong>Route Name:</strong> <?php echo $route_details[0]['route_name']; ?></p>
                <p><strong>Distance:</strong> <?php echo $route_details[0]['distance']; ?> km</p>

                <h3>Available Buses</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bus Number</th>
                            <th>Capacity</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($route_details as $detail): ?>
                            <tr>
                                <td><?php echo $detail['bus_number']; ?></td>
                                <td><?php echo $detail['capacity']; ?></td>
                                <td><?php echo $detail['departure_time']; ?></td>
                                <td><?php echo $detail['arrival_time']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (isset($route_id)): ?>
                <div class="alert alert-warning">No details found for the selected route.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Section for Selecting Station and Viewing Assigned Employees -->
<section class="content-section">
    <div class="container">
        <div class="glass-card">
            <h2>See Assigned Employees in a Station</h2>
            <form method="POST" action="index.php">
                <label for="station_select" class="form-label">Select a Station</label>
                <select name="station_id" id="station_select" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select a Station --</option>
                    <?php while ($row = $result_stations->fetch_assoc()): ?>
                        <option value="<?php echo $row['station_id']; ?>" <?php echo isset($_POST['station_id']) && $_POST['station_id'] == $row['station_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['station_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <?php
            if (isset($_POST['station_id']) && $_POST['station_id'] != '') {
                $station_id = $_POST['station_id'];

                // Fetch employees assigned to the selected station
                $sql_assigned_employees = "
                    SELECT e.employee_id, e.name, e.role
                    FROM employee e
                    WHERE e.station_id = ?";
                $stmt = $conn->prepare($sql_assigned_employees);
                $stmt->bind_param("i", $station_id);
                $stmt->execute();
                $result_assigned_employees = $stmt->get_result();
            ?>

            <h3>Assigned Employees</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_assigned_employees->num_rows > 0): ?>
                        <?php while ($row = $result_assigned_employees->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2" class="text-center">No employees assigned to this station.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Sections for Employees, Buses, and Routes -->
<section class="content-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <div class="glass-card">
                    <div class="mb-4">
                        <h2 class="mb-0">Employees</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result_employees->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['employee_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td><?php echo $row['salary']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="glass-card">
                    <div class="mb-4">
                        <h2 class="mb-0">Buses</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Bus Number</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result_buses->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['bus_id']; ?></td>
                                    <td><?php echo $row['bus_number']; ?></td>
                                    <td><?php echo $row['capacity']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>