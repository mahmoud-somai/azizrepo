<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
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
$sql = "SELECT username, email, soldeconge FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $soldeconge);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
  <nav>
    <div class="logo">
      <h1>User Profile</h1>
    </div>
    <ul class="nav-links">
      <li><a href="user.php">Profile</a></li>
      <li><a href="leave_list.php">List Of Leave</a></li>
      <li><a href="leave_balance.php">Leave Balance</a></li>
      <li><a href="#">Settings</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="profile-container">
    <div class="profile-header">User Profile</div>
    <div class="profile-description">
      <p>Welcome to your profile. Here you can view your information and manage your settings.</p>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
      <p><strong>Leave Balance:</strong> <?php echo htmlspecialchars($soldeconge); ?> days</p>
    </div>
  </div>
</body>
</html>
