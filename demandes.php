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

// Fetch pending leave requests
$sql = "SELECT * FROM leaves JOIN users ON leaves.user_id = users.id WHERE leaves.status = 'pending'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Leave Requests</title>
  <link rel="stylesheet" href="profile.css">
  <script>
    function handleLeaveRequest(id, action) {
        fetch('handle_leave_request.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&action=${action}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
  </script>
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
    <div class="profile-header">Pending Leave Requests</div>
    <div class="profile-description">
      <table>
        <tr>
          <th>Username</th>
          <th>Leave Type</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['leave_type']) . "</td><td>" . htmlspecialchars($row['start_date']) . "</td><td>" . htmlspecialchars($row['end_date']) . "</td><td><button onclick=\"handleLeaveRequest(" . $row['leave_id'] . ", 'approve')\">Approve</button><button onclick=\"handleLeaveRequest(" . $row['leave_id'] . ", 'reject')\">Reject</button></td></tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No pending leave requests</td></tr>";
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
