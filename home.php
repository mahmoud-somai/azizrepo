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

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
  <nav>
    <div class="logo">
      <h1>Admin Profile</h1>
    </div>
    <ul class="nav-links">
      <li><a href="home.php">Profile</a></li>
      <li><a href="employees.php">Employees</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="profile-container">
    <div class="profile-header">Admin Profile</div>
    <div class="profile-description">
      <p>Welcome to the Admin Dashboard. Here you can manage employees, adjust settings, and view your profile information.</p>
      <p><strong>Admin Name:</strong> <?php echo htmlspecialchars($username); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>
    <div class="admin-actions">
      <button  onclick="window.location.href='list_leave.php'">Leave List</button>
      <button onclick="window.location.href='demandes.php'">Demandes</button>
    </div>
  </div>
</body>
</html>
