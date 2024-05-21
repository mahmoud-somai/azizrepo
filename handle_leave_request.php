<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "congedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $leave_id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'approved';
    } else if ($action === 'reject') {
        $status = 'rejected';
    } else {
        echo "Invalid action";
        exit();
    }

    // Update leave request status
    $sql = "UPDATE leaves SET status = ? WHERE leave_id = ?";
   
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("si", $status, $leave_id);

    if ($stmt->execute()) {
        echo "Leave request $action successfully!";
    } else {
        echo "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}

$conn->close();
?>
