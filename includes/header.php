<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Bus Employee Management' : 'Bus Employee Management System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="glass-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="./index.php" class="text-decoration-none">
                    <h4 class="gradient-text m-0">Home</h4>
                </a>
                <div>
                <a href="./admin/login.php" class="btn btn-outline-light btn-sm">Admin</a>
                </div>
            </div>
        </div>
    </nav>