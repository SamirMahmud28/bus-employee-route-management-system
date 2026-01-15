<?php
session_start();

// Define the hardcoded admin credentials
$admin_user_id = 'admin123'; // Example admin user ID
$admin_password = 'pass123'; // Example admin password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // Check if entered user ID and password match the hardcoded credentials
    if ($user_id === $admin_user_id && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user_id;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid User ID or Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
        }
        .hero-section {
            background-color: #343a40;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-title {
            font-size: 3rem;
        }
        .gradient-text {
            background: linear-gradient(90deg, #f5a623, #d35400);
            -webkit-background-clip: text;
            color: transparent;
        }
        .login-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-card .card-header {
            background-color: #343a40;
            color: white;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title gradient-text">Bus and Employee Management System</h1>
            <p class="lead">Admin Login</p>
        </div>
    </section>

    <!-- Admin Login Section -->
    <section class="content-section">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <div class="login-card">
                        <div class="card-header">
                            <h3 class="mb-0">Admin Login</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" id="user_id" name="user_id" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
