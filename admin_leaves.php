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
$sql = "SELECT id, username, leave_type, start_date, end_date FROM leaves WHERE status = 'pending'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave Requests</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
  <script>
    function handleLeaveRequest(id, action) {
        if (!confirm(`Are you sure you want to ${action} this leave request?`)) {
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "handle_leave_request.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText);
                location.reload(); // Reload the page to reflect the changes
            }
        };
        xhr.send("id=" + id + "&action=" + action);
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
      <li><a href="admin_leaves.php">Leave List</a></li>
      <li><a href="admin_demandes.php">Demandes</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="profile-container">
    <div class="profile-header">Leave Requests</div>
    <div class="profile-description">
      <h2>Pending Leave Requests</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Leave Type</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['leave_type']) . "</td><td>" . htmlspecialchars($row['start_date']) . "</td><td>" . htmlspecialchars($row['end_date']) . "</td><td><button onclick=\"handleLeaveRequest(" . $row['id'] . ", 'approve')\">Approve</button> <button onclick=\"handleLeaveRequest(" . $row['id'] . ", 'reject')\">Reject</button></td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No pending leave requests</td></tr>";
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
