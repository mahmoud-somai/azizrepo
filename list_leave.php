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

// Fetch approved leaves
$sql = "SELECT users.username, leaves.leave_type, leaves.start_date, leaves.end_date FROM leaves JOIN users ON leaves.user_id = users.id WHERE leaves.status = 'approved'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Approved Leaves</title>
  <link rel="stylesheet" href="profile.css">
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
    <div class="profile-header">Approved Leaves</div>
    <div class="profile-description">
      <table>
        <tr>
          <th>Username</th>
          <th>Leave Type</th>
          <th>Start Date</th>
          <th>End Date</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['leave_type']) . "</td><td>" . htmlspecialchars($row['start_date']) . "</td><td>" . htmlspecialchars($row['end_date']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No approved leaves</td></tr>";
        }
        ?>
      </table>
    </div>
  </div>
</body>
</html>
<?php
$conn->close();
?>
