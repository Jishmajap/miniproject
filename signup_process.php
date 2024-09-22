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
$name = $_POST['name'];
$email = $_POST['emaiid'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];

// Check if passwords match
if ($password !== $confirmpassword) {
    die("Passwords do not match.");
}


// Insert user into database
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: login.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>