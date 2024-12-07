<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare SQL query based on user type
    if ($userType == 'admin') {
        $query = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
    } else {
        $query = "INSERT INTO shop_owners (name, email, password) VALUES (?, ?, ?)";
    }

    // Create a prepared statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Account created successfully.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }
    $conn->close();
    header("Location: signup.php");
    exit();
}
?>