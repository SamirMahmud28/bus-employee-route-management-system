<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $conn->begin_transaction();

    try {
        // Delete from specialized tables
        $conn->query("DELETE FROM supervisor WHERE employee_id = '$employee_id'");
        $conn->query("DELETE FROM driver WHERE employee_id = '$employee_id'");
        $conn->query("DELETE FROM admin WHERE employee_id = '$employee_id'");

        // Delete from employee table
        $sql = "DELETE FROM employee WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        $conn->commit();
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
header("Location: dashboard.php");
exit();
?>
