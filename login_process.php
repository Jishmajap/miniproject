<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['username'];
$password = $_POST['password'];

// Check if user exists
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists, verify password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        echo "Login successful!";
        // Redirect to a protected page or dashboard
        header("Location: location.php");
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with this email.";
}

$conn->close();
?>