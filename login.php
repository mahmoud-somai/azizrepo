<?php
session_start();

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

// Retrieve form data
$email = $_POST['email'];
$pass = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT id, username, role, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($user_id, $username, $role, $hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0) {
    // Verify the password
    if (password_verify($pass, $hashed_password)) {
        // Password is correct
        $_SESSION['user_id'] = $user_id; // Store user ID in session
        $_SESSION['username'] = $username; // Store fullname in session
        $_SESSION['role'] = $role; // Store role in session
        
        if ($role === 'admin') {
            header("Location: home.php");
        } else {
            header("Location: user.php");
        }
        exit();
    } else {
        // Invalid password
        echo "Invalid email or password";
    }
} else {
    // Invalid email
    echo "Invalid email or password";
}

$stmt->close();
$conn->close();
?>
