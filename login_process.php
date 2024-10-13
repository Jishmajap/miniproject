<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Log form details to the console
    echo "<script>console.log('Username: " . $email . "');</script>";
    echo "<script>console.log('Password: " . $password . "');</script>";

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Regenerate session ID
            session_regenerate_id(true);

            // Store user data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];

            // Redirect to dashboard
            if (!headers_sent()) {
                header("Location: dashboard.php");
                exit();
            } else {
                // If headers already sent, use JavaScript redirect
                echo "<script>window.location.href='index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.'); window.location.href='login.php';</script>";
    }

    $stmt->close();
}

$conn->close();
ob_end_flush();
?>