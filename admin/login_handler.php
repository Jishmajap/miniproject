<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query based on user type
    if ($userType == 'admin') {
        $query = "SELECT * FROM admins WHERE email = ?";
    } else {
        $query = "SELECT * FROM shop_owners WHERE email = ?";
    }

    // Create a prepared statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // User authenticated successfully
                $_SESSION['email'] = $email;
                $_SESSION['userType'] = $userType;

                if ($userType == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: shop_dashboard.php");
                }
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            // No user found with that email
            $_SESSION['error'] = "Invalid email or password.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
    header("Location: login.php");
    exit();
}
?>