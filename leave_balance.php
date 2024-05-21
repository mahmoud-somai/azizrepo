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
  <title>Leave Balance</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
        $('form').on('submit', function(event) {
            event.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: 'submit_leave.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                    window.location.href = 'user.php';
                },
                error: function() {
                    alert('Error submitting leave request.');
                }
            });
        });
    });
  </script>
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
    <div class="profile-header">Leave Balance</div>
    <div class="profile-description">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
      <p><strong>Leave Balance:</strong> <?php echo htmlspecialchars($soldeconge); ?> days</p>
      <h2>Request Leave</h2>
      <form>
        <label for="leave_type">Leave Type:</label>
        <select name="leave_type" id="leave_type" required>
          <option value="Vacation">Vacation</option>
          <option value="Sick Leave">Sick Leave</option>
          <option value="Personal Leave">Personal Leave</option>
        </select>
        <br>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" required>
        <br>
        <button type="submit">Submit</button>
      </form>
    </div>
  </div>
</body>
</html>
